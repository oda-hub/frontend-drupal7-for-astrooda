<?php

namespace Drupal\entity_content_export\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Define the entity export download controller.
 */
class EntityExportDownload implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * @var null|\Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * @var array|\Drupal\Core\Config\ImmutableConfig
   */
  protected $configuration;

  /**
   * The entity export download constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configuration
   */
  public function __construct(
    RequestStack $request_stack,
    ConfigFactoryInterface $configuration
  ) {
    $this->request = $request_stack->getCurrentRequest();
    $this->configuration = $configuration->get('entity_content_export.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container
  ) {
    return new static (
      $container->get('request_stack'),
      $container->get('config.factory')
    );
  }

  /**
   * Build the download results page.
   *
   * @return array
   *   A Drupal render array.
   */
  public function downloadResults() {
    $request = $this->getRequest();

    if (!$request->query->has('results')) {
      throw new NotFoundHttpException(
        $this->t('Missing entity content export download results.')
      );
    }
    $results = $request->query->get('results');

    $query_options = [
      'query' => [
        'file' => isset($results['file']) ? $results['file'] : NULL,
      ]
    ];
    $download_url = Url::fromRoute(
      'entity_content_export.download', [], $query_options
    )->toString();

    $build = [
      '#markup' => $this->t(
        "The download should automatically start shortly. If it doesn't, click
         <a data-auto-download href='@download_url'>Download</a>.", [
          '@download_url' => $download_url
        ]
      ),
      '#attached' => [
        'library' => [
          'entity_content_export/auto-download'
        ]
      ]
    ];

    return $build;
  }

  /**
   * Download entity content exported file.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function downloadExport() {
    $file_uri = $this->getRequest()->query->get('file');

    if (!isset($file_uri) || !file_exists($file_uri)) {
      throw new NotFoundHttpException(
        $this->t('Missing or not found entity content exported file.')
      );
    }

    return (new BinaryFileResponse($file_uri))
      ->deleteFileAfterSend($this->deleteExportedFile())
      ->setContentDisposition('attachment', basename($file_uri))
   ;
  }

  /**
   * Determine if the exported file should be deleted.
   *
   * @return bool
   *   Return TRUE if the file should be deleted; otherwise FALSE.
   */
  protected function deleteExportedFile() {
    return (bool) $this->configuration->get('delete_exported_file') ?? FALSE;
  }

  /**
   * Get current request object.
   *
   * @return null|\Symfony\Component\HttpFoundation\Request
   */
  protected function getRequest() {
    return $this->request;
  }
}
