<?php
$links = [
    [
        'title' => __d('clients', 'personal data'),
        'id' => 'dati',
        'url' => '/clients/resume'
    ],
    [
        'title' => __d('clients', 'logout'),
        'id' => 'logout',
        'url' => '/clients/logout',
        'extraClass' => ' account__menu__link--light'
    ],
]
?>

<ul class="menu account__menu">
    <?php foreach ($links as $link):
        $extraClass = $link['extraClass'] ?? null;
        $extraClass .= !empty($current) && $current == $link['id'] ? ' account__menu__link--active' : '';
        ?>
        <li>
            <a href="<?= $this->Frontend->url($link['url']) ?>" class="account__menu__link<?= $extraClass ?>" ><?= $link['title'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
