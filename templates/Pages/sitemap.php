<?php
	use Cake\Core\Configure;
	use Cake\Datasource\FactoryLocator;
	
	$filename = 'sitemap_' . ACTIVE_LANGUAGE . '.xml';
	//header("Content-type: text/xml");
    //header("Content-Disposition: attachment; filename=$filename");	
	
	$homepage = ACTIVE_LANGUAGE != DEFAULT_LANGUAGE ? '/' . ACTIVE_LANGUAGE . '/' : '';
	
	
	$customPages = FactoryLocator::get('Table')->get('CustomPages.CustomPages')->find()
								->where(['published' => true])
								->all();
								
	$posts = FactoryLocator::get('Table')->get('Blog.Posts')->find()
								->where(['published' => true, 'locked' => false])
								->all();
	
	$categories = FactoryLocator::get('Table')->get('Products.ProductCategories')->find()
								->where(['published' => true])
								->all();		
	
	$products = FactoryLocator::get('Table')->get('Products.Products')->find()
								->where(['Products.published' => true, 'Products.locked' => false])
								->contain([
									'ProductVariants'
								])
								->all();	
								
	$regions = FactoryLocator::get('Table')->get('Regions.Regions')->find()
								->where([
									'published' => true, 
									'parent_id IS NOT NULL',
									'unlocked_wineries_count > ' => 0
								])
								->all();								
										
	
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?php echo Configure::read('Setup.domain'); ?><?php echo $homepage; ?></loc>			
	</url>
	<url>
		<loc><?php echo Configure::read('Setup.domain'); ?><?php echo $this->Frontend->url('/posts/index'); ?></loc>			
	</url>
	<url>
		<loc><?php echo Configure::read('Setup.domain'); ?><?php echo $this->Frontend->url('/regions/index'); ?></loc>			
	</url>
	<?php
		foreach($customPages as $customPage) {
			if($customPage->id != 1) {
				?>
				<url>
					<loc><?php echo Configure::read('Setup.domain') . $this->Frontend->url('/custom-pages/view/' . $customPage->id); ?></loc>			
				</url>				
				<?php
			}
		}
	?>
	<?php
		foreach($posts as $post) {			
			?>
			<url>
				<loc><?php echo Configure::read('Setup.domain') . $this->Frontend->url('/posts/view/' . $post->id); ?></loc>			
			</url>				
			<?php			
		}
	?>
	<?php
		foreach($regions as $region) {			
			?>
			<url>
				<loc><?php echo Configure::read('Setup.domain') . $this->Frontend->url('/regions/view/' . $region->id); ?></loc>			
			</url>				
			<?php			
		}
	?>
	<?php
		foreach($categories as $category) {			
			?>
			<url>
				<loc><?php echo Configure::read('Setup.domain') . $this->Frontend->url('/product-categories/view/' . $category->id); ?></loc>			
			</url>				
			<?php			
		}
	?>
	<?php
		foreach($products as $product) {			
			?>
			<url>
				<loc><?php echo Configure::read('Setup.domain') . $this->Frontend->url('/products/view/' . $product->id . '/' . $product->product_variants[0]->id); ?></loc>			
			</url>				
			<?php			
		}
	?>
</urlset> 

<?php die; ?>