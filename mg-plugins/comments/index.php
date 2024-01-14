<?php

/*
  Plugin Name: Отзывы покупателей
  Description: Плагин позволяет оставлять отзывы о товарах и статьях сайта. Имеет панель адмнистрирования.
  Author: HollowJ и Avdeev Mark
  Version: 2.3.17
  Edition: CLOUD
 */


$coments = new CommentsToMoguta;
if (URL::isSection('mg-admin')) {
  MG::addInformer(array('count'=>$coments->getNewCommentsCount(),'class'=>'comment-wrap','classIcon'=>'fa-comments', 'isPlugin'=>true, 'section'=>'comments', 'priority'=>80));
 }
class CommentsToMoguta {
  private static $lang = array(); // массив с переводом плагина 
  public static $pluginName = ''; // название плагина (соответствует названию папки)
  private static $path = ''; //путь до файлов плагина 

  public function __construct() {
    self::$pluginName = PM::getFolderPlugin(__FILE__);
    self::$path = PLUGIN_DIR.self::$pluginName;
    self::$lang = PM::plugLocales(self::$pluginName);

    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDataComments'));
    mgAddAction(__FILE__, array(__CLASS__, 'pagePluginComments'));
    mgAddShortcode('comments', array(__CLASS__, 'showComments'));
    mgAddShortcode('wall-comments', array(__CLASS__, 'wallComments'));
    mgAddShortcode('count-comments', array(__CLASS__, 'countComments'));
    mgAddMeta('<script src="'.SITE.'/mg-plugins/comments/js/comments.js"></script>');
    mgAddMeta('<script src="'.SITE.'/mg-plugins/comments/js/schema-org.js"></script>');
    mgAddMeta('<link href="'.SITE.'/mg-plugins/comments/css/style.css" rel="stylesheet" type="text/css">');
    mgAddMeta('<script src="'.SITE.'/mg-core/script/jquery.fancybox.pack.js"></script>');
	  mgAddMeta('<link href="'.SITE.'/mg-core/script/standard/css/jquery.fancybox.css" rel="stylesheet" type="text/css">');
    if (defined('TEMPLATE_INHERIT_FROM')) {
      mgAddMeta('<link href="'.SITE.'/'.PATH_TEMPLATE.'/components/pagination/pagination.css" rel="stylesheet" type="text/css">');
    }
  }

// При активации создает таблицу в БД и регистрирует новую опцию
  static function createDataComments() {
    $sql = "
  		 CREATE TABLE IF NOT EXISTS `".PREFIX."comments` (
  			`id` INT AUTO_INCREMENT NOT NULL,
        `name` VARCHAR(45) NOT NULL,
        `email` VARCHAR(45) NOT NULL,
        `comment` TEXT NOT NULL,
        `date` TIMESTAMP NOT NULL,
        `uri` VARCHAR(255) NOT NULL,
        `approved` TINYINT NOT NULL DEFAULT 0, 
        `img` text NOT NULL,
        PRIMARY KEY(`id`)
  			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";


    DB::query($sql);
    setOption('countPrintRowsComments', 10);
  }

  /**
   * Создает каркас для вывода плагина. Обрабатывается шорткодом
   * @return type 
   */
  static function showComments() {

    $options = unserialize(stripslashes(MG::getOption('commentsOption')));
    $lang = PM::plugLocales(self::$pluginName);

    $html = "
      <div class='comments'>
      <h3>".$lang['COMMENTS_TITLE']."</h3>
      <div class='comments-msg'></div>
        <form>";

    if (!User::getThis()) {
      $html .= "
        <label>".$lang['ENTER_NAME'].":</label>
        <input type='text' name='name' value='' />
        <span class='error'></span>
        <label>".$lang['ENTER_EMAIL'].":</label>
        <input type='text' name='email' value='' />
        <span class='error'></span>
      ";
    }

    $html .= "
      <label>".$lang['LABEL_COMMENT'].":</label>
        <textarea name='comment'></textarea>";

    if ($options['useFiles'] == 'true') {
      $html .= "<a href='javascript:void(0);' class='showImgComments'><i class='fa fa-paperclip'></i> ".$lang['ATTACH_IMG']."</a>
                <div class='comments-dropZone' style='display:none;'>
                  <span>".$lang['DROPZONE_LABEL']."</span>
                  <input name='comments_file_input[]' class='comments_file_input' type='file' accept='.gif,.jpg,.jpeg,.png' multiple>
                </div>";
    }

      $html .= "
        <button class='sendComment'>" . $lang['SEND_COMMENTS'] . "</button>     
		<div class='clear'></div>
        <input type='hidden' name='action' value='addComment'>
      </form>
      <div class='comments_preview'></div>
      <div class='comment-list'>
      ";

    $comments = self::getComments();
    if (!empty($comments['comments'])) {
      foreach ($comments['comments'] as $item) {
        $dateIso = date("Y-m-d", strtotime($item['date']));
        $html .= "
         <div class='comment-post' itemprop=\"review\" itemscope itemtype=\"http://schema.org/Review\">
          <meta itemprop='datePublished' content='".$dateIso."'>
          <span class='post-author' itemprop='author'>".$item['name']."</span>
          <span class='post-date'>".$item['date']."</span>
          <p itemprop='reviewBody'>".$item['comment']."</p>";

          if (strlen($item['img']) > 2) {
            $imgArr = explode('|', $item['img']);
            foreach ($imgArr as $key => $value) {
              $html .= '<a class="fancy-modal" href="'.SITE.'/uploads/comments/'.$value.'"><img src="'.SITE.'/uploads/comments/thumbs/'.$value.'"/></a>';
            }
          }
        $html .= "</div>";
      }
      $html .= $comments['pagination']['html'];
    } else {
      $html .= "<div class='comment-post'>".$lang['COMMENTS_NONE']."</div>";
    }    
 
    return $html.'</div></div>';
  }

  /**
   * Получаем все записи комментариев к этой странице
   * @return type 
   */
  static function getComments() {
    $result = array();
    $where = '';
    if (MG::get('controller')=='controllers_product') {
      $where = " OR uri LIKE '%/".DB::quote(URL::getLastSection(), true)."' OR uri = ".DB::quote("/".URL::getLastSection());
    }
    // Запрос для генерации блока пагинации 
    $sql = "
      SELECT *
      FROM `".PREFIX."comments` 
      WHERE (uri = ".DB::quote(URL::getClearUri()).$where.") AND approved = '1'
      ORDER BY `date` DESC";

    //Получаем блок пагинации
    if (@$_GET["comm_page"]) {
      $page = $_GET["comm_page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс
    }


    $navigator = new Navigator($sql, @$page, MG::getSetting('countPrintRowsComments'), 6, false, "comm_page"); //определяем класс
    $comments = $navigator->getRowsSql();
    $pagination = $navigator->getPager();

	
    /*
     * Получаем  комментарии.	
     */
    foreach ($comments as $key => $value) {
      $comments[$key]['date'] = date('d.m.Y H:i', strtotime($comments[$key]['date']));
    }

    $result['comments'] = $comments;
    $result['pagination'] = [];
    $result['pagination']['html'] = $pagination;
    if (defined('TEMPLATE_INHERIT_FROM')) {
      ob_start();
      $result['pagination']['firstPages']['ancor'] = '';
      $result['pagination']['lastPages']['ancor'] = '';
      component('pagination', $result['pagination']['html']);
      $result['pagination']['html'] = ob_get_contents();
      ob_end_clean();
    }

    return $result;
  }

  /**
   * Получаем количество новых комментариев 
   */
  static function getNewCommentsCount() {   
    $exist=false;
    $result = DB::query('SHOW TABLES');
      while($row = DB::fetchArray($result)){
        if( PREFIX."comments"==$row[0]){
          $exist=true;
        };
      }
      

    if ($exist){
      $sql = "
        SELECT `id`
        FROM `".PREFIX."comments`
        WHERE `approved`=0";

      $res = DB::query($sql);
      $count = DB::numRows($res); 

    }
    return $count?$count:0;
  }
  
  /**
   * Вывод страницы плагина в админке
   */
  static function pagePluginComments() {

    $dbQuery = DB::query("SHOW COLUMNS FROM `".PREFIX."comments` LIKE 'img'");
    if(!$row = DB::fetchArray($dbQuery)) {
      DB::query("ALTER TABLE `".PREFIX."comments` ADD `img` text NOT NULL");
    }
    
    echo '<link type="text/css" href="'.SITE.'/mg-core/script/standard/css/jquery.fancybox.css" rel="stylesheet">';
    echo '<script src="'.SITE.'/mg-core/script/jquery.fancybox.pack.js"></script>';

    $options = unserialize(stripslashes(MG::getOption('commentsOption')));
    $lang = self::$lang;

    if ($_POST["page"])
      $page = $_POST["page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс

    $countPrintRowsComments = MG::getOption('countPrintRowsComments');
    $navigator = new Navigator("SELECT  *  FROM `".PREFIX."comments` ORDER BY `id` DESC", $page, $countPrintRowsComments); //определяем класс
    $comments = $navigator->getRowsSql();
    $pagination = $navigator->getPager('forAjax');

    // подключаем view для страницы плагина
    include 'pagePlugin.php';
  }
  
   /**
   * Вывод всех комментариев
   */
  static function wallComments() {
    $comments = self::getComments();
    $html .= '<div class="reviews-big">';
    if (!empty($comments['comments'])) {
      foreach ($comments['comments'] as $item) {
        $html .= '
         <div class="review-element"><div class="reviews-info">
          <span class="user-name">'.$item['name'].'</span>
          <span class="add-date">'.$item['date'].'</span>
         </div>   
          <p class="reviews-text">'.$item['comment'].'</p></div>
        ';
      }
      $html .= '</div>'.$comments['pagination']['html'];
    }
   return $html;
  }

  /**
   * Счетчик комментариев по шорткоду (выводится вместе со скобочками)
   */
  static function countComments()
  {
    $where = '';
    if (MG::get('controller')=='controllers_product') {
      $where = " OR uri LIKE '%/".DB::quote(URL::getLastSection(), true)."' OR uri = ".DB::quote("/".URL::getLastSection());
    }

    $sql = "
      SELECT *
      FROM `".PREFIX."comments` 
      WHERE (uri = ".DB::quote(URL::getClearUri()).$where.") AND approved = '1'";

    if (!$result = DB::query($sql)) {
      return "";
    }

    return $result->num_rows;
  }

}
