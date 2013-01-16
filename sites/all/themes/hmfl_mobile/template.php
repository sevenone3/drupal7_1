<?php
/**
* Theme override for theme_menu_link()
* Adds a unique ID class to all menu items.
*/

//                             <div class="fright scont"><a href="#">My Details</a> | <a href="#">Logout</a></div>

function hmfl_mobile_hmfl_plot_group_view($group)
{
  if(is_array($group))
  {
    $group = $group['group']['#element'];
  }

  $output = '<div><div><a href="'.url("development/{$group->development_id}/groups/{$group->id}/edit").'">'.$group->address.'</a></div></div>';
  return $output;
}
function hmfl_mobile_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '';
    $variables['primary']['#suffix'] = '';
    $output .= drupal_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '';
    $variables['secondary']['#suffix'] = '';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}
function hmfl_mobile_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  // remove the view link when viewing the node
  if ($link['path'] == 'node/%/view') return false;
  $link['localized_options']['html'] = TRUE;
//   die(url($link['href']));
  return '<input type="button" onclick="location.href=\''.url($link['href']).'\'" value="'.$link['title'].'"/>';
  return l($link['title'], $link['href'], $link['localized_options'])."\n";
}

function hmfl_mobile_table($variables) {
  $header = $variables['header'];
  $rows = $variables['rows'];
  $attributes = $variables['attributes'];
  $caption = $variables['caption'];
  $colgroups = $variables['colgroups'];
  $sticky = $variables['sticky'];
  $empty = $variables['empty'];

  $attributes['class'] = 'pl-list';
  $output = '<table' . drupal_attributes($attributes) . ">\n";

  if (isset($caption)) {
    $output .= '<caption>' . $caption . "</caption>\n";
  }

  // Format the table columns:
  if (count($colgroups)) {
    foreach ($colgroups as $number => $colgroup) {
      $attributes = array();

      // Check if we're dealing with a simple or complex column
      if (isset($colgroup['data'])) {
        foreach ($colgroup as $key => $value) {
          if ($key == 'data') {
            $cols = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $cols = $colgroup;
      }

      // Build colgroup
      if (is_array($cols) && count($cols)) {
        $output .= ' <colgroup' . drupal_attributes($attributes) . '>';
        $i = 0;
        foreach ($cols as $col) {
          $output .= ' <col' . drupal_attributes($col) . ' />';
        }
        $output .= " </colgroup>\n";
      }
      else {
        $output .= ' <colgroup' . drupal_attributes($attributes) . " />\n";
      }
    }
  }

  // Add the 'empty' row message if available.
  if (!count($rows) && $empty) {
    $header_count = 0;
    foreach ($header as $header_cell) {
      if (is_array($header_cell)) {
        $header_count += isset($header_cell['colspan']) ? $header_cell['colspan'] : 1;
      }
      else {
        $header_count++;
      }
    }
    $rows[] = array(array(
        'data' => $empty,
        'colspan' => $header_count,
        'class' => array('empty', 'message'),
      ));
  }

  // Format the table header:
  if (count($header)) {
    $ts = tablesort_init($header);
    // HTML requires that the thead tag has tr tags in it followed by tbody
    // tags. Using ternary operator to check and see if we have any rows.
    $output .= (count($rows) ? ' <thead><tr>' : ' <tr>');
    foreach ($header as $cell) {
      $cell = tablesort_header($cell, $header, $ts);
      $output .= _theme_table_cell($cell, TRUE);
    }
    // Using ternary operator to close the tags based on whether or not there are rows
    $output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
  }
  else {
    $ts = array();
  }

  // Format the table rows:
  if (count($rows)) {
    $output .= "<tbody>\n";
    $flip = array(
      'even' => 'odd',
      'odd' => 'even',
    );
    $class = 'even';
    foreach ($rows as $number => $row) {
      $attributes = array();

      // Check if we're dealing with a simple or complex row
      if (isset($row['data'])) {
        foreach ($row as $key => $value) {
          if ($key == 'data') {
            $cells = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $cells = $row;
      }
      if (count($cells)) {
        // Add odd/even class
        if (empty($row['no_striping'])) {
          $class = $flip[$class];
          $attributes['class'][] = $class;
        }

        // Build row
        $output .= ' <tr' . drupal_attributes($attributes) . '>';
        $i = 0;
        foreach ($cells as $cell) {
          $cell = tablesort_cell($cell, $header, $ts, $i++);
          $output .= _theme_table_cell($cell);
        }
        $output .= " </tr>\n";
      }
    }
    $output .= "</tbody>\n";
  }

  $output .= "</table>\n";
  return $output;
}

// function hmfl_variables($hook, $vars) {
//   switch ($hook) {
//     case 'page':
//       // If the page was requested with the jQuery ajax functionalities, an HTTP header (X-Requested-With: XMLHttpRequest)
//       // will be sent to the server, making it possible to identify if we should serve the content as JSON
//       if (true||(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XmlHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'])) {
//       die("json");
//           // Now that we know that the page was requested via remote scripting (AJAX) we can serve the content as JSON
//           // by telling Drupal to use a different template for the page (in this case page-json.tpl.php)
//           $vars['template_files'] = is_array($vars['template_files']) ? $vars['template_files'] : array();
//           $vars['template_files'][] = 'page-json';
//       }
//       break;
//   }
// }

// function hmfl_links__system_main_menu($variables) {
//   $base_path = base_path();
//   $html = "<nav>";
//   $html .= '  <ul id="menu">'; 
// 
//   foreach ($variables['links'] as $link) {
// 
//     $class = "";
//     if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
//           && (empty($link['language']) || $link['language']->language == $language_url->language)) {
//         $class .= ' active';
//     }
//     $html .= "<li id=\"".strtolower($link['title'])."\" class=\"".$class."\"><a href=\"".url($link['href'])."\" class=\"$class\" ><div>".$link['title']."</div>";
//     
//     if(isset($link['menu_icon'])&&$link['menu_icon']['enable'])
//     {
//       $html.="<div class=\"img\"><img src=\"".$base_path.$link['menu_icon']['path']."\" /></div>";
//     }
//     $html.="</a></li>";
//   }
//   $html .= "  </ul>";
//   $html .= "</nav>";
// 
//   return $html;
//  }
// 
// function hmfl_menu_link(array $variables) {
//   $element = $variables['element'];
//   $sub_menu = '';
//   $base_path = base_path()."/";
//   if ($element['#below']) {
//     $sub_menu = drupal_render($element['#below']);
//   }
// 
//   $title = "";
//   if(isset($element['#localized_options']['menu_icon'])&&$element['#localized_options']['menu_icon']['enable'])
//   {
//     $title.= "<img src=\"".$element['#localized_options']['menu_icon']['path']."\" /> ";
//     $element['#attributes']['class'][] = "image";
//   }
//   $output ='<li ' . drupal_attributes($element['#attributes']) . '>';
//   $title.=$element['#title'];
//   $element['#localized_options']['html'] = true;
//   $output.= l($title, $element['#href'], $element['#localized_options']);
//   return  $output . $sub_menu . "</li>\n";
// }

// function hmfl_pager($variables) {
//   $tags = $variables['tags'];
//   $element = $variables['element'];
//   $parameters = $variables['parameters'];
//   $quantity = $variables['quantity'];
//   global $pager_page_array, $pager_total;
// 
//   // Calculate various markers within this pager piece:
//   // Middle is used to "center" pages around the current page.
//   $pager_middle = ceil($quantity / 2);
//   // current is the page we are currently paged to
//   $pager_current = $pager_page_array[$element] + 1;
//   // first is the first page listed by this pager piece (re quantity)
//   $pager_first = $pager_current - $pager_middle + 1;
//   // last is the last page listed by this pager piece (re quantity)
//   $pager_last = $pager_current + $quantity - $pager_middle;
//   // max is the maximum page number
//   $pager_max = $pager_total[$element];
//   // End of marker calculations.
// 
//   // Prepare for generation loop.
//   $i = $pager_first;
//   if ($pager_last > $pager_max) {
//     // Adjust "center" if at end of query.
//     $i = $i + ($pager_max - $pager_last);
//     $pager_last = $pager_max;
//   }
//   if ($i <= 0) {
//     // Adjust "center" if at start of query.
//     $pager_last = $pager_last + (1 - $i);
//     $i = 1;
//   }
//   // End of generation loop preparation.
// 
//   if ($pager_total[$element] > 1) {
// 
//     // When there is more than one page, create the pager list.
//     if ($i != $pager_max) {
//       if ($i > 1) {
//         $items[] = array(
//           'class' => array('pager-ellipsis'), 
//           'data' => '…',
//         );
//       }
//       // Now generate the actual pager piece.
//       for (; $i <= $pager_last && $i <= $pager_max; $i++) {
//        if ($i < $pager_current) {
//           $items[] = array(
//             'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
//           );
//         }
//         if ($i == $pager_current) {
//           $items[] = array(
//             'class' => array('pager-current',"selected"), 
//             'data' => "<a href='javascript:void(0)'>".$i."</a>",
//           );
//         }
//         if ($i > $pager_current) {
//           $items[] = array(
//             'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
//           );
//         }
//       }
//       if ($i < $pager_max) {
//         $items[] = array(
//           'class' => array('pager-ellipsis'), 
//           'data' => '…',
//         );
//       }
//     }
// 
//     return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
//       'items' => $items, 
//       'attributes' => array('class' => array('pager','nav')),
//     ));
//   }
// }
