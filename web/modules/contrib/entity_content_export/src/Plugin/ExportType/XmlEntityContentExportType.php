<?php

namespace Drupal\entity_content_export\Plugin\ExportType;

use Drupal\Core\Annotation\Translation;
use Drupal\entity_content_export\Annotation\EntityContentExportType;
use Drupal\entity_content_export\EntityContentExportTypeBase;

/**
 * Define CSV entity content export type.
 *
 * @EntityContentExportType(
 *   id = "xml",
 *   label = @Translation("XML"),
 *   format = "xml"
 * )
 */
class XmlEntityContentExportType extends EntityContentExportTypeBase {

  /**
   * {@inheritDoc}
   */
  protected function writePrependedData($handle) {
    return fwrite($handle, "<?xml version=\"1.0\"?>\r\n<entities>\r\n");
  }

  /**
   * {@inheritDoc}
   */
  protected function writeData($handle, array $data, $is_last = FALSE) {
    $element = $this->createEntityXmlElement($data);

    if ($element === FALSE) {
      return FALSE;
    }

    return fwrite($handle, $element . PHP_EOL);
  }

  /**
   * {@inheritDoc}
   */
  protected function writeAppendedData($handle) {
    return fwrite($handle, '</entities>');
  }

  /**
   * Create entity XML element.
   *
   * @param array $data
   *   An array of data to format as XML.
   *
   * @return string
   *   The entity XML element.
   */
  protected function createEntityXmlElement(array $data) {
    $document = new \DOMDocument();

    $document->formatOutput = TRUE;
    $document->preserveWhiteSpace = FALSE;

    $entity = $document->createElement('entity');
    $this->appendXmlElement($document, $entity, $data);
    $document->appendChild($entity);

    return $document->saveXML($document->documentElement);
  }

  /**
   * Append an XML element to the parent element.
   *
   * @param \DOMDocument $document
   *   The DOM document instance.
   * @param \DOMElement $parent_element
   *   The parent DOM element instance.
   * @param array $values
   *   An array of values to convert to an XML structure.
   *
   * @return $this
   */
  protected function appendXmlElement(
    \DOMDocument $document,
    \DOMElement $parent_element,
    array $values
  ) {
    foreach ($values as $key => $value) {
      if (!is_integer($key)) {
        $element = $document->createElement($key);

        if (is_array($value)) {
          $this->appendXmlElement($document, $element, $value);
        }
        else {
          $this->formatXMLElementValue($element, $value);
        }

        $parent_element->appendChild($element);
      }
      else {
        $this->appendXmlElement($document, $parent_element, $value);
      }
    }

    return $this;
  }

  /**
   * Format XML element value.
   *
   * @param \DOMElement $element
   *   A DOM element instance.
   * @param $value
   *   The DOM element value to set.
   */
  protected function formatXMLElementValue(\DOMElement &$element, $value) {
    if (!is_string($value)) {
      throw new \InvalidArgumentException(
        'Incorrect data type given for the "value" argument. Only a string is
         permissible when formatting the value.'
      );
    }
    if ($value !== strip_tags($value)) {
      $element->appendChild(
        $element->ownerDocument->createCDATASection($value)
      );
    }
    else {
      $element->nodeValue = $value;
    }
  }
}
