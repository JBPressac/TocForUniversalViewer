<?php
/**
 * Table of content for Universal Viewer
 *
 *
 * @copyright Jean-Baptiste Pressac, 2018
 * @license https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html
 *  */

/**
 * A Table of content for the UniversalViewer plugin.
 * @package Omeka\Plugins\TocForUniversalViewer
 */
class TocForUniversalViewerPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_filters = array(
      'uv_manifest',
    );

    const ELEMENT_SET_NAME = 'PDF Table of Contents';
    const ELEMENT_NAME = 'Text';

    protected $_pdfMimeTypes = array(
        'application/pdf',
        'application/x-pdf',
        'application/acrobat',
        'text/x-pdf',
        'text/pdf',
        'applications/vnd.pdf',
    );

    public function filterUvManifest($manifest, $args)
    {
      // Fonction executée à la consultation des pages des manifestes, per ex.
      // http://localhost/omeka26/iiif/4/manifest

      if(array_key_exists('record', $args)){

        // Trouvé les paramètres du filtre uv_manifest en cherchant apply_filters dans
        // le plugin UniversalViewer et en consultant la documentation
        // de apply_filters.
        $record = $args['record'];
        // The class test is inspired by https://tinyurl.com/yaro5pq6
        $recordClass = get_class($record);
        if($recordClass == 'Item'){
          $item = $record;
          $files = $item->getFiles();

          // Il est possible de modifier le titre du document dans la visionneuse
          // comme suit.
          // $manifest['label'] = "Label bleu" ;

          // Ce qui suit est inspiré de https://tinyurl.com/ya8osg94

          foreach ($files as $file) {
            if (in_array($file->mime_type, $this->_pdfMimeTypes)) {
              $textElement = $file->getElementTexts(
                self::ELEMENT_SET_NAME,
                self::ELEMENT_NAME
              );
              $toc = $textElement[0];

              if (!preg_match("/InfoValue/", $toc)) {
                /* On est dans le cas où la métadonnée Text du jeux de métadonnées
                PDF Table of Contents peut ressembler à :

                1|Soun Fantik|3
                1|Kola|5
                1|Eun eureud|7
                1|Ar c'haor|9
                1|Izabel|11

                */

                $sortie = "";
                $toc = rtrim($toc);

                $tab_toc = preg_split("/\n/", $toc);
                $niveau_pdt = "";
                $total = (count($tab_toc)-1);
                for ($i = 0; $i <= $total; $i++)
                {
                  $tab_ligne = preg_split("/\|/", $tab_toc[$i]);
                  $niveau = $tab_ligne[0];
                  $titre  = $tab_ligne[1];
                  $page   = $tab_ligne[2];

                  $range = $i + 1;

                  $manifest['structures'][] =
                    array('@id' => absolute_url("iiif/" . $item->id . "/range/r" . $range),
                      '@type' => "sc:Range",
                      'label' => $titre,
                      'canvases' => array(absolute_url("iiif/" . $item->id . "/canvas/p" . $page))
                      );
                }
              }

              /*
              $manifest['structures'][] =
                array('@id' => "http://localhost/omeka26/iiif/3/range/r0",
                      '@type' => "sc:Range",
                      'label' => "Page 01",
                      'canvases' => array("http://localhost/omeka26/iiif/3/canvas/p1")
                    );
              $manifest['structures'][] =
                array('@id' => "http://localhost/omeka26/iiif/3/range/r1",
                      '@type' => "sc:Range",
                      'label' => "Page 10",
                      'canvases' => array("http://localhost/omeka26/iiif/3/canvas/p10")

                  );
              */
            } // End if in_array
          }  // End foeach
        }  // End of class test
      } // End of test array-key-exists

      return $manifest ;
    }
}
