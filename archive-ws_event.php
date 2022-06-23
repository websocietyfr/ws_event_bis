<?php get_header(); ?>
<section class="py-5 text-center thumbnail_post">
    <div class="filigrane"></div>
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light"><?php echo get_the_archive_title(); ?></h1>
        </div>
    </div>
</section>
<main class="container">
    <section class="publications mt-5">
        <?php while(have_posts()): ?>
            <?php the_post(); ?>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="img-fluid rounded-start" alt="<?php echo get_the_title(); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h5>
                            <p class="card-text">
                                <span>Du <?php
                                        $dateStart = date_create(get_post_meta(get_the_ID(), 'startDate', true));
                                        echo date_format($dateStart, 'd/m/Y - H:i');
                                    ?> au <?php
                                        $dateEnd = date_create(get_post_meta(get_the_ID(), 'endDate', true));
                                        echo date_format($dateEnd, 'd/m/Y - H:i');
                                    ?></span>
                            </p>
                            <p class="card-text"><small class="text-muted">par <?php echo get_the_author(); ?></small></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
</main>
<?php get_footer();
