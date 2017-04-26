<?php namespace KosmosKosmos\ChModder\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class ChModder extends ReportWidgetBase {

    public function render(){
        
        $this->vars['folders'] = $this->getFolders();

        return $this->makePartial("widget");
    }

    private function getFolders() {
        return [
            "Storage &amp; Media" => base_path()."/storage",
            "Theme Folders" => base_path()."/themes",
            "Plugin Folders" => base_path()."/plugins"
        ];
    }

    public function defineProperties() {
        return [
            'title' => [
                'title'             => 'ChModder Widget',
                'default'           => 'Unlock your directories',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'Error in widget configuration.'
            ],
        ];
    }

    private function recursiveChmod ($path = "", $filePerm=0666, $dirPerm=0777) {

        // Check if the path exists
        if (!file_exists($path)) {
            return(false);
        }

        // See whether this is a file
        if (is_file($path)) {
            // Chmod the file with our given filepermissions
            try {
                chmod($path, $filePerm);
            } catch (Exception $e) {
                // Nicht alle konnten bearbeitet werden
            }

        // If this is a directory...
        } elseif (is_dir($path)) {
            // Then get an array of the contents
            $foldersAndFiles = scandir($path);

            // Remove "." and ".." from the list
            $entries = array_slice($foldersAndFiles, 2);

            // Parse every result...
            foreach ($entries as $entry) {
                // And call this function again recursively, with the same permissions
                $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
            }

            // When we are done with the contents of the directory, we chmod the directory itself
            try {
                chmod($path, $dirPerm);
            } catch (Exception $e) {
                    // Nicht alle konnten bearbeitet werden
            }
            
        }

        // Everything seemed to work out well, return true
        return(true);
    }

    public function onChMod(){

        $folders = $this->getFolders();
        $result = array();

        foreach(post("folder") as $folder ) {
            $result[$folder] = (array_key_exists($folder, $folders)) ?  
                $this->recursiveChmod($folders[$folder]) : -1;
        }
        
        return [
            'partial' => $this->makePartial("result",["result"=>$result])
        ];
    }

}
