<?php get_header(); ?>
    <?php the_post(); ?>
    <section class="py-5 text-center thumbnail_post">
        <img src="<?php echo get_the_post_thumbnail_url(); ?>"/>
        <div class="filigrane"></div>
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><?php echo get_the_title(); ?></h1>
                <p class="lead text-muted"><?php echo get_the_excerpt(); ?></p>
                <p>
                    <label>Publié le</label> <?php echo get_the_date(); ?><br>
                    <label>Publié par</label> <?php echo get_the_author(); ?>
                </p>
            </div>
        </div>
    </section>
    <main class="container">
        <section class="mt-5 row">
            <div class="col-3">
                <p>Début :</p>
                <p><?php
                    $dateStart = date_create(get_post_meta(get_the_ID(), 'startDate', true));
                    echo date_format($dateStart, 'd/m/Y - H:i');
                ?></p>
            </div>
            <div class="col-3">
                <p>Fin :</p>
                <p><?php
                    $dateEnd = date_create(get_post_meta(get_the_ID(), 'endDate', true));
                    echo date_format($dateEnd, 'd/m/Y - H:i');
                ?></p>
            </div>
            <div class="col-3">
                <p>S'inscrire à l'événement :</p>
                <p><a href="<?php echo get_post_meta(get_the_ID(), 'linkToRegister', true); ?>" target="_blank">Cliquez ici</a></p>
            </div>
            <div class="col-3">
                <p>Documentation de l'événement :</p>
                <p><a href="<?php echo get_post_meta(get_the_ID(), 'linktoDocumentation', true); ?>" target="_blank">Cliquez ici</a></p>
            </div>
        </section>
        <section class="mt-5">
            <?php the_content(); ?>
        </section>
    </main>
<?php get_footer();
