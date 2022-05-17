<?php
/**
 * 2022 Genkiware
 *
 * NOTICE OF LICENSE
 *
 * This file is licenced under the GNU General Public License, version 3 (GPL-3.0).
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 *  @author     Genkiware <info@genkiware.com>
 *  @copyright  2022 Genkiware
 *  @license    https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace Genkiware\PresetNote\classes;

class GenkiTools
{
    /**
     * Get a single configuration value (in all languages)
     *
     * @param string $key Key wanted
     *
     * @return array Value
     */
	public static function getConfigMultiLang($key, $idShopGroup = null, $idShop = null, $default = false) {
        $result = [];
        foreach (Language::getLanguages(false, false, true) as $idLang){
            $result[$idLang] = Configuration::get($key, $idLang, $idShopGroup, $idShop, $default);
        }
        return $result;
    }

    /**
     * Get all sub-folders in a folder
     * 
     * @see https://www.w3schools.com/Php/func_filesystem_glob.asp
     * 
     * @param string $path Path of the folder you want to search
     * @param bool $nameonly If only names should be return (i.e. not full path, folder name only)
     * 
     * @return array List of sub-folders
     */
    public static function getFoldersInDir($path, $nameonly = false) {
        $path = (substr($path, -1) == '/') ? $path . '*' : $path . '/*' ;
        $flds = glob($path, GLOB_ONLYDIR);

        if ($nameonly) {
            foreach ($flds as &$fld) {
                $fld = basename($fld);
            }
        }
        return $flds;
    }

    /**
     * Get all files with specific file extension in a folder
     * 
     * @see https://www.w3schools.com/Php/func_filesystem_glob.asp
     * 
     * @param string $path Path of the folder you want to search
     * @param string $ext File extension (Start with ".")
     * @param bool $nameonly If only names should be return (i.e. no file extension)
     * 
     * @return array List of files
     */
    public static function getFilesInDir($path, $ext, $nameonly = false) {
        $path = (substr($path, -1) == '/') ? $path . '*' : $path . '/*' ;
        $files = glob($path . $ext);

        foreach ($files as &$file) {
            if ($nameonly) {
                $file = basename($file, $ext);
            } else {
                $file = basename($file);
            }
        }
        return $files;
    }
}