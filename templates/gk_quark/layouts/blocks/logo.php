<?php

// No direct access.
defined('_JEXEC') or die;
$logo_image = $this->API->get('logo_image', '');
$dark_logo_image = $this->API->get('dark_logo_image', '');

if(($logo_image == '') || ($this->API->get('logo_type', '') == 'css')) {
     $logo_image = $this->API->URLtemplate() . '/images/logo.png';
} else {
     $logo_image = $this->API->URLbase() . $logo_image;
}

if($dark_logo_image !== '') {
     $dark_logo_image = $this->API->URLbase() . $dark_logo_image;
}

$logo_text = $this->API->get('logo_text', '');
$logo_slogan = $this->API->get('logo_slogan', '');

?>

<?php if ($this->API->get('logo_type', 'image')!=='none'): ?>
     <?php if($this->API->get('logo_type', 'image')=='text') : ?>
     <a href="<?php echo JURI::root(); ?>" id="gkLogo" class="text">
		<span><?php echo $this->API->get('logo_text', ''); ?></span>
        
        <?php if($this->API->get('logo_slogan', '') != '') : ?>
        <small class="gkLogoSlogan"><?php echo $this->API->get('logo_slogan', ''); ?></small>
        <?php endif; ?>
     </a>
     <?php elseif($this->API->get('logo_type', 'image')=='image') : ?>
	 <a href="<?php echo JURI::root(); ?>" id="gkLogo">
        <img 
        	src="<?php echo $logo_image; ?>" 
        	<?php if($dark_logo_image != '') : ?>data-dark="<?php echo $dark_logo_image; ?>"<?php endif; ?> 
        	<?php if($dark_logo_image != '') : ?>data-light="<?php echo $logo_image; ?>"<?php endif; ?>
        	alt="<?php echo $this->API->getPageName(); ?>" 
        />
     </a>
     <?php endif; ?>
<?php endif; ?>