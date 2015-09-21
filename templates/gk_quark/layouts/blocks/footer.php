<?php

// No direct access.
defined('_JEXEC') or die;

?>

<footer id="gkFooter">
	<div class="gkPage">
		<?php if($this->API->modules('footer_nav')) : ?>
		<div id="gkFooterNav">
			<jdoc:include type="modules" name="footer_nav" style="none" modnum="<?php echo $this->API->modules('footer_nav'); ?>" />
		</div>
		<?php endif; ?>
		
		<?php if($this->API->get('copyrights', '') !== '') : ?>
		<div id="gkCopyrights"><?php echo $this->API->get('copyrights', ''); ?></div>
		<?php else : ?>
		<div id="gkCopyrights">Template Design &copy; by <a href="https://www.gavick.com/" title="GavickPro" rel="nofollow">GavickPro</a>. All rights reserved.</div>
		<?php endif; ?>
	</div>
</footer>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                
                <div class="pswp__preloader"></div>
                
                <button class="pswp__button pswp__button--fs" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_TOGGLE_FULLSCREEN'); ?>"></button>
                <button class="pswp__button pswp__button--zoom" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_ZOOM'); ?>"></button>
                <button class="pswp__button pswp__button--share" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_SHARE'); ?>"></button>
                <button class="pswp__button pswp__button--close" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_CLOSE'); ?>"></button>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_PREV'); ?>"></button>
            <button class="pswp__button pswp__button--arrow--right" title="<?php echo JText::_('TPL_GK_LANG_PHOTOSWIPE_NEXT'); ?>"></button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>