<?
ini_set('include_path',ini_get('include_path').'.:'.$_SERVER['DOCUMENT_ROOT'].'/phplib/LightweightPicasaAPIv3/');
include ($_SERVER['DOCUMENT_ROOT'].'/phplib/LightweightPicasaAPIv3/Picasa.php');
function get_picasa_album($user, $album_name)
{
  // если есть кеш, берем данные из кеша
  if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name) )
  {
      $album_data = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name));
  }
  else       
  {
      // получаем данные через API picasa
      $pic = new Picasa();
      // получаем данные для альбома, в последнем параметре указываем размеры необходимых изображений. Можно также указать размеры: 72, 144, 200, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600
      // http://googlesystem.blogspot.com/2006/12/embed-photos-from-picasa-web-albums.html
      $album = $pic->getAlbumById($user, $album_name,null,null,null,null,'144,640');

      // получаем данные о изображениях в альбоме
      $images = $album->getImages();
      foreach ($images as $image)
      {
          $thumbnails = $image->getThumbnails();
          $album_data['images'][] = array('url'=>(string)$thumbnails[1]->getUrl(),
                                          'width'=>(string)$thumbnails[1]->getWidth(),
                                          'height'=>(string)$thumbnails[1]->getHeight(),
                                          'title'=>(string)$image->getDescription(),
                                          'tn_url'=>(string)$thumbnails[0]->getUrl(),
                                          'tn_width'=>(string)$thumbnails[0]->getWidth(),
                                          'tn_height'=>(string)$thumbnails[0]->getHeight(),
                                    );
                     
      }
      // иконка альбома, размеры стандартные 160 на 160
      $album_data['url'] = (string)$album->getIcon();
      $album_data['width'] = '160';
      $album_data['height'] = '160';
      $album_data['title'] = (string)$album->getTitle();
      
      // сохраняем данные в кеш
      if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user))
            mkdir($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user,0777);
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/picasa_api_cache/'.$user.'/'.$album_name,serialize($album_data));
  }
     
  return $album_data;
}

$title = "picasa test";
include "../tmpl/header.php";

$i = get_picasa_album('BudnikPV','100117');

print '<table cellspacin="0" cellpadding="15" border="0" width="100%">';
print '<tr><td>';
print '<h1><img width="50" height="50" src="'.$i['url'].'" style="vertical-align: middle; border: none;" />&nbsp;&nbsp;'.iconv("UTF-8","WINDOWS-1251",$i['title']).'</h1><br /><br /><br />';

foreach ($i['images'] as $img)
    print '<a href="'.$img['url'].'"><img src="'.$img['tn_url'].'" hspace="10" vspace="10" style="border:none;" /></a> ';

print '</td></tr></table>';

include "../tmpl/footer.php";

?>