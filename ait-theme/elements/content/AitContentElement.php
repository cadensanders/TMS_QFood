<?php


class AitContentElement extends AitElement
{
	// override AitElement class to enable element by default
	public function isEnabled()
	{
		if($this->config['@disabled'] === false){
			return true;
		}else{
			return false;
		}
	}

	public function isDisplay()
	{
		global $post;

		if(is_singular(array('post', 'page'))){
			if(isset($post) and empty($post->post_content)){
				return false;
			}else{
				return parent::isDisplay();
			}
		}elseif(AitWoocommerce::currentPageIs('woocommerce')){
			return parent::isDisplay();
		}else{
			return parent::isDisplay();
		}
	}
}
