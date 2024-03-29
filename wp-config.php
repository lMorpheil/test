<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL

// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', "test" );

/** Имя пользователя базы данных */
define( 'DB_USER', "root" );

/** Пароль к базе данных */
define( 'DB_PASSWORD', "" );

/** Имя сервера базы данных */
define( 'DB_HOST', "localhost" );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'p(<|[T8&?Cec^/(9i6_Q1z!B+- e;=.$&L5w?N(}T9{`B6U2&z_*nHP:GcZ|hocs');
define('SECURE_AUTH_KEY',  'tC^9W.]:+CSVNT1u!U1>bdb6|gP%wJS9xh[G}g *+cYCKMhp)4Z)Pl$d;$DiTF&f');
define('LOGGED_IN_KEY',    'Odnc`BM#cgF>aXk;nf&@}3zsa#H]M#UFKj?%.g{k!?X%585_ZDF%u[Ko.#|P>eI=');
define('NONCE_KEY',        'tv*+~$K_0<2)5VaYjN!TZ/-pS+nG{+Uv33+5R6&,^gIcVDOnpl/8nw*aAKHQ;N8!');
define('AUTH_SALT',        '-zTq{IrHXp@l17WZX(+kT1_i-GB|]<oT?lU-Ou-kds1%0tZHqse_(We+Y;>Yf:BY');
define('SECURE_AUTH_SALT', '3m51GBz`{ai?:vr:;TLoVfmuyzZ2_~gM@<H2r?^YKv4R~P3;7?^}TU3[U$W23Q!t');
define('LOGGED_IN_SALT',   '#tkQ^YH9iHGK(IzNjS&_4k}eUJaG7]NOkZCBEe-aNm(VQ6IKFA.z#V=v)-B||{d;');
define('NONCE_SALT',       'O~:|}: | :xl~[O}V|vRboB7{O?cm.ZE@okgv P.Z2wz,s^J!=q^W{&~Toe!N BJ');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
define( 'WP_SITEURL', 'http://test-local.ru/' );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
