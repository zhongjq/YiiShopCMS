<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class myLinkPager extends CLinkPager
{
		const CSS_SELECTED_PAGE='active';
        const CSS_HIDDEN_PAGE='disabled';
    public $pageVar = "page";
    
		protected function createPageButton($label,$page,$class,$hidden,$selected)
        {
            if($hidden || $selected)
                $class.=" ".($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
                
            return '<li'.( $class ? ' class="'.$class.'"' : "").'>'.CHtml::link($label,$this->createPageUrl($page)).'</li>';
        }
        

}

?>