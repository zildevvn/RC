<?php
$services = get_field('list_services');
?>
<?php if (!empty($services)): ?>
    <section class="ui-section service-section">
        <div class="container">
            <div class="service-section__box" data-aos="fade-up">
                <div class="service-section__grid">
                    <?php foreach ($services as $service): ?>
                        <div class="service-section__item">
                            <div class="service-section__icon">
                                <img src="<?= $service['icon'] ?>"
                                    alt="rent car in da nang for serivce <?= $service['heading'] ?>" />
                            </div>
                            <div>
                                <h3 class="service-section__title h6">
                                    <?= $service['heading'] ?>
                                </h3>
                                <p class="service-section__desc">
                                    <?= $service['sub_heading'] ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>