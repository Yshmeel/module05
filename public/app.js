jQuery(document).ready(function() {
    function applicationEmailCheckLogic($jobItem, jobId, $emailField) {
        let debounceEmailTimer = -1;

        $emailField.on('change', function() {
            const fieldValue = jQuery(this).val();

            if(fieldValue === "") {
                return false;
            }

            window.clearTimeout(debounceEmailTimer);
            debounceEmailTimer = setTimeout(() => {
                jQuery.ajax({
                    url: '/application/email',
                    data: {
                        email: fieldValue
                    },

                    beforeSend: function() {
                        $jobItem.find('input').prop('disabled', true);
                    },

                    success: function(msg) {
                        $jobItem.find('#name-field').val(msg.name).trigger('change');
                        $jobItem.find('#phone-field').val(msg.phone).trigger('change');
                    },

                    complete: function() {
                        $jobItem.find('input').prop('disabled', false);
                    }
                });

                jQuery.ajax({
                    url: '/application/skills',
                    data: {
                        email: fieldValue,
                        job_id: jobId
                    },

                    success: function(msg) {
                        msg.skills.forEach((skill) => {
                            $jobItem.find(`#competence-${skill.competence_id}`).val(skill.level_id);
                        });
                    },
                });
            }, 600);
        });
    }

    function applicationFormValidationLogic($jobItem) {
        function checkCanIUnblockSubmitButton() {
            const $inputs =  $jobItem.find('input');

            let filledCount = 0;

            $inputs.each(function() {
                if(jQuery(this).val() !== '') {
                    filledCount++;
                }
            });

            $jobItem.find('button[type="submit"]').prop('disabled', filledCount !== $inputs.length);
        }

        $jobItem.find('input').on('change', function() {
            checkCanIUnblockSubmitButton();
        });
    }

    jQuery('.job__item').each(function() {
        const jobId = jQuery(this).attr('data-job-id'),
              $slideElement = jQuery(this).find('.job__item__slide');

        jQuery(this).find('.slide-toggler').click(function() {
            if($slideElement.is(":visible")) {
                $slideElement.slideUp(150);
                jQuery(this).html('Open');
            } else {
                $slideElement.slideDown(150);
                jQuery(this).html('Close');
            }
        });

        applicationEmailCheckLogic(jQuery(this), jobId, jQuery($slideElement).find('#email-field'));
        applicationFormValidationLogic(jQuery(this));
    });

    jQuery('.job__item__slide--application').each(function() {
        const $slideElement = jQuery(this).find('.job__item__slide--application--slide');

        jQuery(this).find('.application-toggler').click(function() {
            if($slideElement.is(":visible")) {
                $slideElement.slideUp(150);
                jQuery(this).html('Open');
            } else {
                $slideElement.slideDown(150);
                jQuery(this).html('Close');
            }
        });
    });
});
