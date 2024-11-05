<?php

namespace Drupal\booktree\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class BooktreeController extends ControllerBase {

  /*
   * This will output the book from the start point specified in the
   * arguments, or take the default in the configuration
   * The arguments are in the order: start node/depth/max title length
   * If only two arguments are present then the max title length is set to 256
   */
  public function tree() {
    $content = '';
    $alias_path = \Drupal::request()->getpathInfo();
    $path_args = explode('/', $alias_path);
    $cache_tags = [];
    \Drupal::logger('booktree')->debug("Generated content for booktree");



    if (isset($path_args[2])) {
      $booktree_start = $path_args[2];
      $maxricursione = (isset($path_args[3]))  ? $path_args[3] + 2 : \Drupal::config('booktree.settings')->get('booktree_deep')+2;
      $trimval = (isset($path_args[4]))  ? $path_args[4]  : 256;
    }
    else {
      $booktree_start = \Drupal::config('booktree.settings')->get('booktree_start');
      $maxricursione = \Drupal::config('booktree.settings')->get('booktree_deep') + 2;
      $trimval = \Drupal::config('booktree.settings')->get('booktree_trim');
    }

    $node = \Drupal::entityTypeManager()->getStorage('node')->load($booktree_start);
    $cache_tags[] = "node:$booktree_start";
    $cache_tags[] = 'bid:' . $node->book['bid'];
    $content .= "<p>{$node->body->value}</p>";
    $content .= $this->booktree_mostra_figli($node, $node, 1, $maxricursione, $trimval, $cache_tags);

    return [
      '#type' => 'markup',
      '#title' => t(':title', [':title' => $node->getTitle()]),
      '#markup' => $content,
      '#cache' => [
        'contexts' => ['url.path'], //setting cache contexts
        'tags' => $cache_tags // setting cache tags
      ],
    ];

  }


  private function booktree_mostra_figli($node, $node_start, $ricursione, $maxricursione, $trimval, &$cache_tags) {
    $content = $c = '';
    if ($ricursione < $maxricursione) {

      $sql = "SELECT DISTINCT b.nid, b.pid, b.weight
           FROM {book} as b
           inner join {node} as n ON n.nid = b.nid
           WHERE b.pid = :pid
           ORDER by b.weight
           ";

      $sql_args = [':pid' => $node->id()];
      $query = \Drupal::database()->query($sql, $sql_args);
      $children = $query->fetchAll();

      ////Now hide a root book node
      if ($node->id() != $node_start->id()) {
        $link = $node->toLink()->toString();
        $content .= "<li class=\"booktree\">" . $link. "</li>";

      }
      $ricursione++;
      foreach ($children as $child) {
        $leaf = \Drupal::entityTypeManager()
          ->getStorage('node')
          ->load($child->nid);
        $cache_tags[] = "node:$child->nid";
        $c .= $this->booktree_mostra_figli($leaf, $node_start, $ricursione, $maxricursione, $trimval, $cache_tags);
      }
      if (strlen($c) > 2) {
        $content .= "<ul class=\"booktree\">\n" . $c . "</ul>\n";
      }
      return $content;
    }
    else {
      return '';
    }
  }
}
