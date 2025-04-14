<?php 
	// controlla quali sottomenu mostrare aperti o chiusi
	$submenuStatus = $this->request->getSession()->read('SubmenuStatus') ?? []; 		
?>

<?php if (!empty($menuItems)): ?>

    <div class="side-menu-wrapper">
        <ul class="side-menu">
            <?php foreach ($menuItems as $link): ?>
				<?php
					$open = !empty($submenuStatus[$link->id]) ? 'side-menu__item--open' : '';					
				?>
                <li class="side-menu__item <?php echo !empty($link->children) ? 'side-menu__item--hasmenu' : null; ?> <?php echo $open; ?>" data-submenu-id="<?php echo $link->id; ?>">
					<a class="side-menu__link" href="<?= $this->Url->build($link->url) ?>">
						<?= $this->Backend->materialIcon($link->icon) ?>
						<div class="side-menu__link__title"><?= $link->title ?></div>
						<?php if (!empty($link->icon)): ?>
						<?php endif; ?>
					</a>
                    <?php if (!empty($link->children)): ?>
                        
                        <div class="side-menu__submenu-wrapper">
                            <ul class="side-menu__submenu" id="<?php echo 'submenu-'. $link->id;?>">
                                <?php foreach ($link->children as $link): 
                                    $extraClass = !empty($link->children) ? 'side-menu__submenu__link--parent' : '';
                                    ?>
                                    <li class="side-menu__submenu__item">
                                        <a href="<?= $this->Url->build($link->url) ?>" class="side-menu__submenu__link <?= $extraClass ?>">
                                            <?= $link->title ?>
                                        </a>

                                        <?php if (!empty($link->children)): ?>
                                            <ul class="side-menu__subsubmenu-wrapper">
                                                <?php foreach ($link->children as $link): ?>
                                                    <li class="side-menu__subsubmenu__item">
                                                        <a href="<?= $this->Url->build($link->url) ?>" class="side-menu__subsubmenu__link">
                                                            <?= $link->title ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
