<?php
namespace App\Model\Entity\Traits;

use Cake\Utility\Text;

trait UrlSettingTrait
{
    /**
     * Trims slashes and prepends the url with a slash
     * If the input is invalid - such as an empty string - the url will become null.
     *
     * @param string $url The url that is to be set
     * @return string
     */
    public function _setUrl($url)
    {
        if (strlen($url) === 0) {
            return '';
        }

        $url = Text::slug($url, [
            'lowercase' => true,
            'replacement' => '-',
        ]);
        $url = '/' . trim($url, '/');
        if ($url === '/') {
            $url = null;
        }

        return $url;
    }
}
