<?php
/**
 * Table of content for Universal Viewer
 *
 *
 * @copyright Jean-Baptiste Pressac, 2018
 * @license https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
 *  */

/**
 * The Table of content for UniversalViewer plugin.
 * @package Omeka\Plugins\TocForUniversalViewer
 */
class TocForUniversalViewerPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
      'public_items_show',
    );

    protected $_filters = array(
      'uv_manifest',
    );

    /**
     * Print out Hello on item show
     */
    public function hookPublicItemsShow()
    {
           echo '<h1>Hello World</h1>';
    }

    public function filterUvManifest($manifest, $args)
    {
      // echo '<p>' + $manifest + '</p>';
      // $structures = '"structures":[{"@id":"https://wellcomelibrary.org/iiif/b18035723/range/r-0","@type":"sc:Range","label":"Front Cover","canvases":["https://wellcomelibrary.org/iiif/b18035723/canvas/c0"]},{"@id":"https://wellcomelibrary.org/iiif/b18035723/range/r-2","@type":"sc:Range","label":"Title Page","canvases":["https://wellcomelibrary.org/iiif/b18035723/canvas/c3"]},{"@id":"https://wellcomelibrary.org/iiif/b18035723/range/r-1","@type":"sc:Range","label":"Back Cover","canvases":["https://wellcomelibrary.org/iiif/b18035723/canvas/c1"]}]'
      $manifest['label'] = "Label bleu" ;
      $manifest['structures'] = array(
        array('@id' => "http://localhost/omeka26/iiif/3/range/r0",
              '@type' => "sc:Range",
              'label' => "Page 01",
              'canvases' => array("http://localhost/omeka26/iiif/3/canvas/p1")
            ),
        array('@id' => "http://localhost/omeka26/iiif/3/range/r1",
              '@type' => "sc:Range",
              'label' => "Page 10",
              'canvases' => array("http://localhost/omeka26/iiif/3/canvas/p10")
            )
    );

      // $manifest = $manifest;
      return $manifest ;
    }
}
