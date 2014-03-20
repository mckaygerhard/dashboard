<div class='ocDashboard bookmarks items'>

    <?php
    $style = "";
    foreach ($additionalparams['bookmarks'] as $bookmark) { ?>
        <div class='ocDashboard bookmarks item' <?php print_unescaped($style); ?>>
            <?php
                if ($bookmark['title'] == "") {
                    $titel = $bookmark['url'];
                } else {
                    $titel = $bookmark['title'];
                }
            ?>
            <a href="<?php p($bookmark['url']); ?>"><?php p($titel); ?></a>
        </div>
    <?php }	?>
</div>

<style type="text/css">
    .ocDashboard.bookmarks {
        text-align: left;
        font-weight: bold;
    }
    .ocDashboard.bookmarks.item {
        border-top: 1px solid #ddd;
        transition: background-color 0.3s;
        border-radius: 3px;
    }
    .ocDashboard.bookmarks.item:hover {
        background: #eee;
    }
    .ocDashboard.bookmarks.item a {
        display: block;
        padding: .3em .5em;
    }
</style>