<?php
$services = get_field('list_services');
?>
<?php if (!empty($services)): ?>
    <section class="ui-section service-section p-0 relative z-30">
        <div class="container relative z-30 -translate-y-1/4 sm:-translate-y-[30%] md:-translate-y-1/2">
            <div class="service-section__box rounded-2xl shadow-2xl p-5 sm:px-10 md:px-6 lg:px-12 py-6 sm:py-8" data-aos="fade-up">
                <div class="service-section__grid grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 md:gap-0">
                    <?php foreach ($services as $service): ?>
                        <div class="service-section__item flex items-start gap-3.5 sm:gap-4">
                            <div class="service-section__icon w-10.5 h-10.5 sm:w-12 sm:h-12 shrink-0 p-2.5 sm:p-3 rounded-xl flex items-center justify-center">
                                <img class="w-full h-auto max-w-[20px] sm:max-w-[24px] rounded-none" src="<?= $service['icon'] ?>"
                                    alt="rent car in da nang for serivce <?= $service['heading'] ?>" />
                            </div>
                            <div>
                                <h3 class="service-section__title h6 mt-0 mb-1.5 sm:mb-1">
                                    <?= $service['heading'] ?>
                                </h3>
                                <p class="service-section__desc m-0 text-xs sm:text-sm">
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