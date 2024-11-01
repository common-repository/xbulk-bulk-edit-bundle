<?php

namespace wpbel\classes\services\export\handlers;

defined('ABSPATH') || exit(); // Exit if accessed directly

use wpbel\classes\repositories\Post;
use wpbel\classes\services\export\Export_Interface;

class XML_Handler implements Export_Interface
{
    private $wxr_version;
    private $post_repository;
    private $wpdb;

    public function __construct()
    {
        $this->post_repository = new Post();

        require_once ABSPATH . 'wp-admin/includes/export.php';
        $this->wxr_version = WXR_VERSION;

        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function export($data)
    {
        $file_name = "wpbel-post-export-" . time() . '.xml';
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Content-Type: text/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="' . esc_attr(get_bloginfo('charset')) . "\" ?>\n";
        the_generator('export');

?>
        <rss version="2.0" xmlns:excerpt="http://wordpress.org/export/<?php echo esc_attr($this->wxr_version); ?>/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/<?php echo esc_attr($this->wxr_version); ?>/">

            <channel>
                <title><?php bloginfo_rss('name'); ?></title>
                <link><?php bloginfo_rss('url'); ?></link>
                <description><?php bloginfo_rss('description'); ?></description>
                <pubDate><?php echo esc_html(gmdate('D, d M Y H:i:s +0000')); ?></pubDate>
                <language><?php bloginfo_rss('language'); ?></language>
                <wp:wxr_version><?php echo esc_html($this->wxr_version); ?></wp:wxr_version>
                <wp:base_site_url><?php echo esc_url($this->wxr_site_url()); ?></wp:base_site_url>
                <wp:base_blog_url><?php bloginfo_rss('url'); ?></wp:base_blog_url>

                <?php $this->wxr_authors_list($data['post_ids']); ?>

                <?php do_action('rss2_head'); ?>

                <?php
                if ($data['post_ids']) {
                    $posts = $this->post_repository->get_posts([
                        'posts_per_page' => -1,
                        'post__in' => array_map('intval', $data['post_ids'])
                    ]);

                    if (!empty($posts->found_posts)) {
                        foreach ($posts->posts as $post) {
                            setup_postdata($post);
                            $is_sticky = is_sticky($post->ID) ? 1 : 0;
                ?>
                            <item>
                                <title><?php echo esc_html($post->post_title); ?></title>
                                <link><?php the_permalink_rss(); ?></link>
                                <pubDate><?php echo esc_html(mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false)); ?></pubDate>
                                <dc:creator><?php echo esc_html($this->wxr_cdata(get_the_author_meta('login'))); ?></dc:creator>
                                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                                <description></description>
                                <content:encoded><?php wp_kses_post($post->post_content); ?></content:encoded>
                                <excerpt:encoded><?php wp_kses_post($post->post_excerpt); ?></excerpt:encoded>
                                <wp:post_id><?php echo esc_html($post->ID); ?></wp:post_id>
                                <wp:post_date><?php echo esc_html($this->wxr_cdata($post->post_date)); ?></wp:post_date>
                                <wp:post_date_gmt><?php echo esc_html($this->wxr_cdata($post->post_date_gmt)); ?></wp:post_date_gmt>
                                <wp:post_modified><?php echo esc_html($this->wxr_cdata($post->post_modified)); ?></wp:post_modified>
                                <wp:post_modified_gmt><?php echo esc_html($this->wxr_cdata($post->post_modified_gmt)); ?></wp:post_modified_gmt>
                                <wp:comment_status><?php echo esc_html($this->wxr_cdata($post->comment_status)); ?></wp:comment_status>
                                <wp:ping_status><?php echo esc_html($this->wxr_cdata($post->ping_status)); ?></wp:ping_status>
                                <wp:post_name><?php echo esc_html($this->wxr_cdata($post->post_name)); ?></wp:post_name>
                                <wp:status><?php echo esc_html($this->wxr_cdata($post->post_status)); ?></wp:status>
                                <wp:post_parent><?php echo esc_html($post->post_parent); ?></wp:post_parent>
                                <wp:menu_order><?php echo esc_html($post->menu_order); ?></wp:menu_order>
                                <wp:post_type><?php echo esc_html($this->wxr_cdata($post->post_type)); ?></wp:post_type>
                                <wp:post_password><?php echo esc_html($this->wxr_cdata($post->post_password)); ?></wp:post_password>
                                <wp:is_sticky><?php echo esc_html($is_sticky); ?></wp:is_sticky>
                                <?php if ('attachment' === $post->post_type) : ?>
                                    <wp:attachment_url><?php echo esc_html($this->wxr_cdata(wp_get_attachment_url($post->ID))); ?></wp:attachment_url>
                                <?php endif; ?>
                                <?php $this->wxr_post_taxonomy($post); ?>
                                <?php
                                $postmeta = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->wpdb->postmeta} WHERE post_id = %d", $post->ID)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                                foreach ($postmeta as $meta) :
                                ?>
                                    <wp:postmeta>
                                        <wp:meta_key><?php echo esc_html($this->wxr_cdata($meta->meta_key)); ?></wp:meta_key>
                                        <wp:meta_value><?php echo esc_html($this->wxr_cdata($meta->meta_value)); ?></wp:meta_value>
                                    </wp:postmeta>
                                <?php
                                endforeach;

                                $_comments = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->wpdb->comments} WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                                $comments  = array_map('get_comment', $_comments);
                                foreach ($comments as $c) :
                                ?>
                                    <wp:comment>
                                        <wp:comment_id><?php echo esc_html($c->comment_ID); ?></wp:comment_id>
                                        <wp:comment_author><?php echo esc_html($this->wxr_cdata($c->comment_author)); ?></wp:comment_author>
                                        <wp:comment_author_email><?php echo esc_html($this->wxr_cdata($c->comment_author_email)); ?></wp:comment_author_email>
                                        <wp:comment_author_url><?php echo esc_url($c->comment_author_url); ?></wp:comment_author_url>
                                        <wp:comment_author_IP><?php echo esc_html($this->wxr_cdata($c->comment_author_IP)); ?></wp:comment_author_IP>
                                        <wp:comment_date><?php echo esc_html($this->wxr_cdata($c->comment_date)); ?></wp:comment_date>
                                        <wp:comment_date_gmt><?php echo esc_html($this->wxr_cdata($c->comment_date_gmt)); ?></wp:comment_date_gmt>
                                        <wp:comment_content><?php echo esc_html($this->wxr_cdata($c->comment_content)); ?></wp:comment_content>
                                        <wp:comment_approved><?php echo esc_html($this->wxr_cdata($c->comment_approved)); ?></wp:comment_approved>
                                        <wp:comment_type><?php echo esc_html($this->wxr_cdata($c->comment_type)); ?></wp:comment_type>
                                        <wp:comment_parent><?php echo esc_html($c->comment_parent); ?></wp:comment_parent>
                                        <wp:comment_user_id><?php echo esc_html($c->user_id); ?></wp:comment_user_id>
                                        <?php
                                        $c_meta = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->wpdb->commentmeta} WHERE comment_id = %d", $c->comment_ID)); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
                                        foreach ($c_meta as $meta) :
                                        ?>
                                            <wp:commentmeta>
                                                <wp:meta_key><?php echo esc_html($this->wxr_cdata($meta->meta_key)); ?></wp:meta_key>
                                                <wp:meta_value><?php echo esc_html($this->wxr_cdata($meta->meta_value)); ?></wp:meta_value>
                                            </wp:commentmeta>
                                        <?php endforeach; ?>
                                    </wp:comment>
                                <?php endforeach; ?>
                            </item>
                <?php
                        }
                    }
                }
                ?>
            </channel>
        </rss>
<?php
        die();
    }

    private function wxr_cdata($str)
    {
        if (!seems_utf8($str)) {
            $str = utf8_encode($str);
        }
        // $str = ent2ncr(esc_html($str));
        $str = '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $str) . ']]>';

        return $str;
    }

    private function wxr_site_url()
    {
        if (is_multisite()) {
            // Multisite: the base URL.
            return network_home_url();
        } else {
            // WordPress (single site): the blog URL.
            return get_bloginfo_rss('url');
        }
    }

    private function wxr_authors_list(array $post_ids = null)
    {
        global $wpdb;

        if (!empty($post_ids)) {
            $post_ids = array_map('absint', $post_ids);
            $and      = 'AND ID IN ( ' . implode(', ', $post_ids) . ')';
        } else {
            $and = '';
        }

        $authors = array();
        $results = $wpdb->get_results("SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and"); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        foreach ((array) $results as $result) {
            $authors[] = get_userdata($result->post_author);
        }

        $authors = array_filter($authors);

        foreach ($authors as $author) {
            echo "\t<wp:author>";
            echo '<wp:author_id>' . esc_html($author->ID) . '</wp:author_id>';
            echo '<wp:author_login>' . esc_html($this->wxr_cdata($author->user_login)) . '</wp:author_login>';
            echo '<wp:author_email>' . esc_html($this->wxr_cdata($author->user_email)) . '</wp:author_email>';
            echo '<wp:author_display_name>' . esc_html($this->wxr_cdata($author->display_name)) . '</wp:author_display_name>';
            echo '<wp:author_first_name>' . esc_html($this->wxr_cdata($author->first_name)) . '</wp:author_first_name>';
            echo '<wp:author_last_name>' . esc_html($this->wxr_cdata($author->last_name)) . '</wp:author_last_name>';
            echo "</wp:author>\n";
        }
    }

    private function wxr_post_taxonomy($post)
    {
        $taxonomies = get_object_taxonomies($post->post_type);
        if (empty($taxonomies)) {
            return;
        }
        $terms = wp_get_object_terms($post->ID, $taxonomies);

        foreach ((array) $terms as $term) {
            echo '<category domain="' . esc_attr($term->taxonomy) . '" nicename="' . esc_attr($term->slug) . '">' . esc_html($this->wxr_cdata($term->name)) . '</category>\n';
        }
    }
}
