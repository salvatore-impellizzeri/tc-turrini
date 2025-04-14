<?php
use Cake\Core\Configure;
use Cake\Routing\Router;

$pageTitle = !empty($pageTitle) ? $pageTitle  : Configure::read('Setup.sitename');
$languages = array_merge([Configure::read('Setup.default_language')], Configure::read('Setup.languages'));
?>
<head>
    <?= $this->Html->charset() ?>
    <title><?= $pageTitle ?></title>
    <?= $this->element('favicon') ?>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <?php
	if(!empty($seoDescription))	echo $this->Html->meta('description', $seoDescription);
	if(!empty($seoKeywords)) echo $this->Html->meta('keywords', $seoKeywords);
	?>

	<link rel="canonical" href="<?php echo Configure::read('Setup.domain'). $this->Frontend->url('/' . $this->Frontend->getCakeUrl()) ?>" />
    <?php
    if(count($languages) > 1){
        $locales = Configure::read('Setup.locale');
        if (IS_HOME) {
            foreach ($languages as $lang) {
    			$url = $lang == DEFAULT_LANGUAGE ? '/' : "/$lang/"
    			?>
    			<link rel="alternate" href="<?php echo $url ?>" hreflang="<?php echo substr($locales[$lang],0,2) ?>" />
    			<?php
    		}
        } else {
            $pageUrl = $this->Frontend->getCakeUrl();
            foreach ($languages as $lang) {
    			?>
    			<link rel="alternate" href="<?php echo $this->Frontend->getLanguageUrl($pageUrl, $lang) ?>" hreflang="<?= $lang ?>" />
    			<?php
    		}
        }
    }
	?>

    <meta property="og:url" content="<?php echo Configure::read('Setup.domain'). $this->Frontend->url('/' . $this->Frontend->getCakeUrl()) ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title"  content="<?= h($pageTitle); ?>" />
	<meta property="og:locale" content="<?= ACTIVE_LANGUAGE ?>" />
	<?php
	foreach (array_diff($languages, array(ACTIVE_LANGUAGE)) as $lang) {
		?>
		<meta property="og:locale:alternate" content="<?= $lang ?>" />
		<?php
	}
	?>
	<?php
	if(!empty($seoDescription)) echo $this->Html->meta(array('property' => 'og:description', 'type' => 'meta', 'content' => $seoDescription, 'rel' => null));
	if(!empty($og_image_for_layout)) echo $this->Html->meta(array('property' => 'og:image', 'type' => 'meta', 'content' => Configure::read('Setup.domain') . $og_image_for_layout, 'rel' => null));
	?>

		<?php 
			// Gestisci le cdn dei font dal file setup se necessario 
			$font = Configure::read('Setup.font');
			if(isset($font) && !empty($font)){
				echo $this->Html->css($font);
			}
		?>

    <?= $this->AssetCompress->css('main'); ?>
	

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

	<?php /*
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=XXXXXXXXXX-XXX"></script>
	<script>	  	  
	  var analyticsConfig = {
		  ad_storage : '<?php echo empty($userCookies["gads"]) ? "denied" : "granted"; ?>',		  		  	  
		  ad_user_data : '<?php echo empty($userCookies["gads"]) ? "denied" : "granted"; ?>',		  		  	  
		  ad_personalization : '<?php echo empty($userCookies["gads"]) ? "denied" : "granted"; ?>',		  		  	  
		  analytics_storage : '<?php echo empty($userCookies["ga"]) ? "denied" : "granted"; ?>'
	  }	 
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
		  
	  gtag('consent', 'default', analyticsConfig);
	   
	  <?php if(empty($userCookies['gads'])): ?>
	  gtag('set', 'ads_data_redaction', true);
	  gtag('set', 'url_passthrough', true);
	  <?php endif; ?>	  	  
	  
	  <?php if(empty($userCookies['gads'])): ?>
	  gtag('set', 'allow_google_signals', false);
	  <?php endif; ?>	  	  

	  gtag('js', new Date());
	  gtag('config', 'XXXXXXXXXX-XXX', {anonymize_ip : true});

	</script>
	*/ ?>

	<?php // if(!empty($userCookies['fb'])) { ?>
	<?php // <!-- Facebook Pixel Code --> ?>
	<?php // } ?>
	
</head>
