<?php

namespace Helper;

use Core\Router;

define('BOOTSTRAP_ALERT_PRIMARY', 0);
define('BOOTSTRAP_ALERT_SECONDARY', 1);
define('BOOTSTRAP_ALERT_SUCCESS', 2);
define('BOOTSTRAP_ALERT_DANGER', 3);
define('BOOTSTRAP_ALERT_WARNING', 4);
define('BOOTSTRAP_ALERT_INFO', 5);
define('BOOTSTRAP_ALERT_LIGHT', 6);
define('BOOTSTRAP_ALERT_DARK', 7);

define('BOOTSTRAP_BUTTON_PRIMARY', 0);
define('BOOTSTRAP_BUTTON_SECONDARY', 1);
define('BOOTSTRAP_BUTTON_SUCCESS', 2);
define('BOOTSTRAP_BUTTON_DANGER', 3);
define('BOOTSTRAP_BUTTON_WARNING', 4);
define('BOOTSTRAP_BUTTON_INFO', 5);
define('BOOTSTRAP_BUTTON_LIGHT', 6);
define('BOOTSTRAP_BUTTON_DARK', 7);
define('BOOTSTRAP_BUTTON_LINK', 8);

define('BOOTSTRAP_MODAL_SIZE_LG', 0);
define('BOOTSTRAP_MODAL_SIZE_MD', 1);
define('BOOTSTRAP_MODAL_SIZE_SM', 2);

class Bootstrap
{
    private static $commonParams = array(
    );

    /**
     * Extract "others" attributes
     *
     * @param mixed $params
     * @param string[] $excludedAttributes
     *
     * @return string
     */
    private static function otherAttributes($params = array(), $excludedAttributes = array())
    {
        $attributes = '';

        $excludedAttributes = array_merge(
            self::$commonParams,
            $excludedAttributes
        );

        foreach ($params as $k => $v) {
            if (!in_array($k, $excludedAttributes)) {
                $attributes .= ' '.$k.'="'.$v.'"';
            }
        }

        return $attributes;
    }

    /**
     * Get bootstrap alert HTML
     * @param string $message
     * @param int $tpye (BOOTSTRAP_ALERT_*)
     * @param bool $canClose
     * @return string
     */
    public static function alert($message = '', $type = BOOTSTRAP_ALERT_SUCCESS, $canClose = true)
    {
        switch($type)
        {
            case BOOTSTRAP_ALERT_SUCCESS:
                $typeClass = 'success';
                break;
            case BOOTSTRAP_ALERT_INFO:
                $typeClass = 'info';
                break;
            case BOOTSTRAP_ALERT_WARNING:
                $typeClass = 'warning';
                break;
            case BOOTSTRAP_ALERT_DANGER:
                $typeClass = 'danger';
                break;
            default:
                $typeClass = $type;
        }
        
        if ($canClose === true) {
            $buttonClose = '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>';
        } else {
            $buttonClose = '';
        }
        
        return
            '<div class="alert alert-'.$typeClass.' alert-dismissible" role="alert">'.
                $buttonClose.
                $message.
            '</div>';
    }
    
    /**
     * Get bootstrap button HTML
     * @param mixed $params
     *      content
     *      icon
     *      type = 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'secondary' | BOOTSTRAP_BUTTON_*
     *      hint
     *      url
     *      newWindow 0 | 1
     *      size = 'lg' | 'sm' | 'xs'
     *      confirmation
     *      id
     *      class
     * @return string
     */
    public static function button($params = null)
    {
        $content = isset($params['content']) ? $params['content'] : '';

        $icon = isset($params['icon']) ? $params['icon'] : '';
        if ($icon != '') {
            $icon = '<i class="fa fa-'.$icon.'"></i>';
            if ($content != '') {
                $icon .= '&nbsp;';
            }
        }
        $content = $icon.$content;

        if (isset($params['type'])) {
            $type = $params['type'];

            if (is_int($type)) {
                switch($type)
                {
					case BOOTSTRAP_BUTTON_PRIMARY:
                        $type = 'primary';
					case BOOTSTRAP_BUTTON_SECONDARY:
                        $type = 'secondary';
					case BOOTSTRAP_BUTTON_SUCCESS:
                        $type = 'success';
					case BOOTSTRAP_BUTTON_DANGER:
                        $type = 'danger';
					case BOOTSTRAP_BUTTON_WARNING:
                        $type = 'warning';
					case BOOTSTRAP_BUTTON_INFO:
                        $type = 'info';
					case BOOTSTRAP_BUTTON_LIGHT:
                        $type = 'light';
					case BOOTSTRAP_BUTTON_DARK:
                        $type = 'dark';
					case BOOTSTRAP_BUTTON_LINK:
                    default:
                        $type = 'link';
                        break;
                }
            }
        } else {
            $type = 'link';
        }
        
        $hint = isset($params['hint']) ? $params['hint'] : '';

        $url = isset($params['url']) ? Router::url($params['url']) : '';

        $target = isset($params['newWindow']) && $params['newWindow'] == '1' ? ' target="_blank"' : '';
        
        if (isset($params['size']) && $params['size'] != '' && $params['size'] != 'md') {
            $size = ' btn-'.$params['size'];
        } else {
            $size = '';
        }

        if (isset($params['confirmation']) && $params['confirmation'] != '') {
            $onclick = ' onclick="return confirm(\''.$params['confirmation'].'\');"';
        } else {
            $onclick = '';
        }
        
        if (isset($params['id']) && $params['id'] != '') {
            $id = ' id="'.$params['id'].'"';
        } else {
            $id = '';
        }

        if (isset($params['class']) && $params['class'] != '') {
            $class = ' '.$params['class'];
        } else {
            $class = '';
        }
        $class = ' class="btn btn-'.$type.$size.$class.'"';

        $otherAttributes = self::otherAttributes(
            $params,
            array(
                'content',
                'icon',
                'type',
                'hint',
                'url',
                'newWindow',
                'size',
                'confirmation',
                'id',
                'class'
            )
        );

        if ($url != '') {
            return '<a href="'.$url.'"'.$class.' title="'.$hint.'"'.$target.$id.$onclick.$otherAttributes.'>'.$content.'</a>';
        } else {
            return '<button type="button"'.$class.' title="'.$hint.'"'.$id.$onclick.$otherAttributes.'>'.$content.'</button>';
        }
    }
    
    /**
     * Get bootstrap HTML for tab control
     * @param mixed $tabs
     * @param bool $fade
     */
    public static function tabs($tabs = array(), $fade = false)
    {
        $tabNav = '';
        $tabContent = '';
        
        foreach($tabs as $t)
        {
            $c = array();
            if(isset($t['active']) && $t['active'] === true)
            {
                $c[] = 'active';
            }
            $class = implode(' ', $c); 
            
            $tabNav .= '<li role="presentation" class="'.$class.'"><a href="#'.$t['id'].'" role="tab" data-toggle="tab">'.$t['label'].'</a></li>';
            
            $c = array('tab-pane');
            if($fade === true)
            {
                $c[] = 'fade';
            }
            if(isset($t['active']) && $t['active'] === true)
            {
                if($fade === true)
                {
                    $c[] = 'in';
                }
                $c[] = 'active';
            }
            $class = implode(' ', $c); 
            
            $tabContent .= '<div id="'.$t['id'].'" class="'.$class.'" role="tabpanel">'.$t['content'].'</div>';
        }
        
        $tabNav = '<ul class="nav nav-tabs" role="tablist">'.$tabNav.'</ul>';
        $tabContent = '<div class="tab-content">'.$tabContent.'</div>';
        
        return $tabNav.$tabContent;
    }

    /**
     * Get bootstrap HTML for accordion control
     * @param mixed $accordion
     */
    static public function accordion($accordion = array())
    {
        $html = '';

        if(count($accordion['panels']) > 0)
        {
            $parent = isset($accordion['multiple']) && $accordion['multiple'] ? '' : '#'.$accordion['id'];
            
            $html .= '<div id="'.$accordion['id'].'" class="panel-group" role="tablist">';
            foreach($accordion['panels'] as $p)
            {
                $collapsed = isset($p['collapsed']) && $p['collapsed'];
                
                $html .=
                    '<div class="panel panel-default">'.
                        '<div id="heading_'.$p['id'].'" class="panel-heading" role="tab">'.
                            '<h4 class="panel-title">'.
                                '<a href="#'.$p['id'].'" class="'.($collapsed ? 'collapsed' : '').'" role="button" data-toggle="collapse" data-parent="'.$parent.'" style="width: 100%;">'.
                                    $p['title'].
                                '</a>'.
                            '</h4>'.
                        '</div>'.
                        '<div id="'.$p['id'].'" class="panel-collapse collapse'.($collapsed ? '' : ' in').'" role="tabpanel">'.
                            '<div class="panel-body">'.
                                $p['content'].
                            '</div>'.
                        '</div>'.
                    '</div>';   
            }
            $html .= '</div>';
        }

        return $html;
    }
    
    /**
     * Get Bootstrap HTML for pagination
     * @param mixed $items
     */
    public static function pagination($firstPage = 1, $lastPage = 1, $currentPage = 1)
    {
        $html = '';
        
        if($lastPage > $firstPage)
        {
            $html .=
                '<nav class="pagination-container">'.
                    '<ul class="pagination">'.
                        '<li'.($firstPage == $currentPage ? ' class="disabled"' : '').'>'.
                            '<a href="?p='.$firstPage.'">&laquo;</a>'.
                        '</li>';
            for($p = $firstPage; $p <= $lastPage; $p++)
            {
                $html .=
                        '<li'.($p == $currentPage ? ' class="active"' : '').'>'.
                            '<a href="?p='.$p.'">'.$p.'</a>'.
                        '</li>';
            }
            $html .=
                        '<li'.($lastPage == $currentPage ? ' class="disabled"' : '').'>'.
                            '<a href="?p='.$lastPage.'">&raquo;</a>'.
                        '</li>'.
                    '</ul>'.
                '</nav>';
        }
            
        return $html;
    }
    
    /**
     * Get bootstrap HTML for modal dialog
     * @param string $id
     * @param string $content
     * @param string $title
     * @param mixed $buttons
     * @param string $class
     * @param int $size BOOTSTRAP_MODAL_SIZE_*
     * @return string
     */
    public static function modal($id = '', $content = '', $title = '', $buttons = array(), $class = '', $size = BOOTSTRAP_MODAL_SIZE_MD)
    {
        $class = rtrim(' '.$class);
        
        switch($size)       
        {
            case BOOTSTRAP_MODAL_SIZE_LG:
                $sizeClass = ' modal-lg';
                break;
            case BOOTSTRAP_MODAL_SIZE_SM:
                $sizeClass = ' modal-sm';
                break;
            case BOOTSTRAP_MODAL_SIZE_MD:
            default:
                $sizeClass = '';
                break;
        }
        
        $buttonsHtml = '';
        foreach($buttons as $b)         
        {
            $buttonsHtml .= Bootstrap::button($b['text'], $b['type']);
        }
        
        $html =         
            '<div id="'.$id.'" class="modal fade'.$class.'" tabindex="-1" role="dialog">'.
                '<div class="modal-dialog'.$sizeClass.'" role="document">'.
                    '<div class="modal-content">'.
                        '<div class="modal-header">'.
                            '<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>'.
                            '<h4 class="modal-title">'.$title.'</h4>'.
                        '</div>'.
                        '<div class="modal-body">'.
                            $content.
                        '</div>'.
                        '<div class="modal-footer">'.
                            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'.
                            $buttonsHtml.
                        '</div>'.
                    '</div>'.
                '</div>'.
            '</div>';               
        
        return $html;
    }
}
