<?php
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

$table = $wpdb->get_blog_prefix() . 'post_likes';

if ($wpdb->get_var("show tables like '$table'") != $table) {
    
    $charset = "DEFAULT CHARACTER SET " . $wpdb->charset . " COLLATE " . $wpdb->collate;
    
    $sql = "CREATE TABLE " . $table . " (
            id bigint(20) unsigned NOT NULL auto_increment,
            address varchar(255) NOT NULL,
            ip varchar(255),
            likes boolean,
            dislikes boolean,
            date_time DATETIME,
            post_id bigint(20) unsigned NOT NULL,
            INDEX(post_id),
            PRIMARY KEY  (id)
    )" . $charset . ";";
    dbDelta($sql);
}

function mytheme_scripts() {
    wp_enqueue_style('main', get_template_directory_uri() . '/assets/main.css');
    wp_enqueue_style('style', get_stylesheet_uri());
    
    wp_enqueue_script('app', get_template_directory_uri() . '/assets/app.js', [], null, true);
}

add_theme_support('post-thumbnails');

add_action('wp_enqueue_scripts', 'mytheme_scripts');

function mytheme_setup() {
    register_nav_menus(
            array(
                    'header_menu' => 'Header Menu',
            )
    );
}

add_action('after_setup_theme', 'mytheme_setup');

function mytheme_widgets_init() {
    register_sidebar(
            array(
                    'name'          => 'sidebar',
                    'id'            => 'sidebar',
                    'class'         => 'inactive-sidebar orphan-sidebar',
                    'description'   => 'sidebar widget',
                    'before_widget' => '<aside class="sidebar">',
                    'after_widget'  => '</aside>',
            )
    );
}

add_action('widgets_init', 'mytheme_widgets_init');

add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);

function my_navigation_template($template, $class) {
    
    return '
        <nav class="navigation %1$s" role="navigation">
            <div class="nav-links">%3$s</div>
        </nav>
        ';
}



add_action('wp_ajax_set_like', 'mytheme_ajax_callback');
add_action('wp_ajax_nopriv_set_like', 'mytheme_ajax_callback');
add_action('wp_enqueue_scripts', 'mytheme_ajax_data', 99);

function mytheme_ajax_data() {
    wp_localize_script('app', 'ajaxUrl',
            array(
                    'url' => admin_url('admin-ajax.php'),
            )
    );
}

function mytheme_ajax_callback () {
    global $table;
    global $wpdb;
    
    $post_id = intval($_POST['id']);
    $address = $_SERVER["HTTP_REFERER"];
    $ip      = $_SERVER['REMOTE_ADDR'];
    $like    = filter_var($_POST['like'], FILTER_VALIDATE_BOOLEAN);
    $dislike = filter_var($_POST['dislike'], FILTER_VALIDATE_BOOLEAN);
    $date    = current_time('mysql');
    
    $get_user_sql = "SELECT ip
                     FROM `$table`
                     WHERE ip = '$ip' AND post_id = '$post_id'";
   $get_like = "SELECT likes
                FROM `$table`
                WHERE ip = '$ip' AND post_id = '$post_id'";
   $get_dislike = "SELECT dislikes
                   FROM `$table`
                   WHERE ip = '$ip' AND post_id = '$post_id'";
    $data =  array(
            'likes'     => $like,
            'dislikes'  => $dislike,
            'date_time' => $date,
    );
    $where_condition = array(
            'ip'      => $ip,
            'post_id' => $post_id,
    );
    
    $get_user = $wpdb->get_var($get_user_sql);
    
    if($get_user) {
        
        if($wpdb->get_var($get_dislike) != $dislike && $wpdb->get_var($get_like) != $like) {
            
            $result = $wpdb->update($table, $data, $where_condition);
            
            wp_die(json_encode(
                    array(
                            'result'      => $result,
                            'is_new_user' => false,
                            'response'    => 'Пользователь решил переголосовать',
                    )
            ));
        }
        
        wp_die(json_encode(
                array(
                        'result'   => false,
                        'response' => 'Пользователь уже делал лайк',
                )
        ));
    }
    
    $data['post_id'] = $post_id;
    $data['address'] = $address;
    $data['ip'] = $ip;
    
    $result = $wpdb->insert($table, $data);
    
    wp_die(json_encode(
            array(
                    'result'      => $result,
                    'is_new_user' => true,
                    'response'    => 'Новый пользователь',
            )
    ));
}

//Админ панель
function mytheme_like_page()
{
    add_menu_page(
            'Лайки постов',
            'Просмотр лайков',
            'manage_options',
            'likes',
            'mytheme_like_table', // функция, которая выводит содержимое страницы
            'dashicons-images-alt2',
            1
    );
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Likes_List_Table extends WP_List_Table {
    
    function get_data_table() {
        global $wpdb;
        
            $row_sql = "SELECT DISTINCT (wpp.post_title),
                        CASE WHEN SUM(wpl.likes) IS NOT NULL THEN SUM(wpl.likes) ELSE 0 END AS likes,
                        CASE WHEN SUM(wpl.dislikes) IS NOT NULL THEN SUM(wpl.dislikes) ELSE 0 END AS dislikes
                        FROM wp_posts wpp
                        LEFT JOIN wp_post_likes wpl
                        ON wpl.post_id = wpp.id
                        WHERE wpp.post_type = 'post'
                        GROUP BY wpp.post_title";
    
        $data_table = $wpdb->get_results($row_sql, ARRAY_A );
        
        return $data_table;
    }
    
    function __construct(){
        global $status, $page;
        
        parent::__construct( array(
                'singular'  => __( 'post', 'mylisttable' ),
                'plural'    => __( 'posts', 'mylisttable' ),
                'ajax'      => false
        ) );
        
        add_action( 'admin_head', array( &$this, 'admin_header' ) );
    }
    
    function no_items() {
        _e( 'Нет данных' );
    }
    
    function get_columns(){
        $columns = array(
                'post_title'    => 'post',
                'likes'    => 'Количество лайков',
                'dislikes' => 'Количество дизлайков',
        );
        
        return $columns;
    }
    
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'post_title':
            case 'likes':
            case 'dislikes':
                return $item[ $column_name ];
            default:
                return print_r( $item, true );
        }
    }
    
    function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = array();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items = $this->get_data_table();
    }
}

add_action('admin_menu', 'mytheme_like_page');

function mytheme_like_table()
{
    $option = 'per_page';
    $args = array(
        'label'   => 'likes',
        'default' => 10,
        'option'  => 'likes_per_page'
    );
    add_screen_option( $option, $args );
    
    $myListTable = new Likes_List_Table();
    
    echo '</pre><div class="wrap"><h2>Статистика лайков</h2>';
    $myListTable->prepare_items();
    ?>
    <form method="post">
        <input type="hidden" name="page" value="likes_list_table">
    <?php
    
    $myListTable->display();
    echo '</form></div>';
}
