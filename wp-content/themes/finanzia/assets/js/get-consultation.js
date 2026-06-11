/**
 * Consultation form — show mortgage/loan sub-type chips when a matching question is chosen.
 * Works on the standalone konzultace page and the header popup-consult modal.
 */
(function ($) {
  'use strict';

  var FORM_ROOT = '.consult-page__cf7, .popup-consult .wpcf7';
  var SUBMIT_ATTEMPTED = 'finaram-cf7-submit-attempted';
  var CONFIG = window.finaramConsultationSubtype || {};
  var MAPS = CONFIG.maps || {};

  function normalizeValue(value) {
    return String(value || '')
      .toLowerCase()
      .trim();
  }

  function flattenQuestionValues(key) {
    var values = [];

    Object.keys(MAPS).forEach(function (lang) {
      var group = MAPS[lang] || {};
      if (Array.isArray(group[key])) {
        values = values.concat(group[key]);
      }
    });

    return values;
  }

  function valueMatchesList(value, list) {
    if (!value || !list.length) {
      return false;
    }

    var normalized = normalizeValue(value);

    return list.some(function (item) {
      return item === value || normalizeValue(item) === normalized;
    });
  }

  function isMortgageType(value) {
    if (valueMatchesList(value, flattenQuestionValues('mortgage'))) {
      return true;
    }

    var v = normalizeValue(value);
    if (!v) {
      return false;
    }
    if (/půjčk|půjčka|\bloan\b/.test(v) && !/hypoték|mortgage/.test(v)) {
      return false;
    }
    return /mortgage|hypoték|hypotéka|hypotéky/.test(v);
  }

  function isLoanType(value) {
    if (valueMatchesList(value, flattenQuestionValues('loan'))) {
      return true;
    }

    var v = normalizeValue(value);
    if (!v) {
      return false;
    }
    if (/hypoték|mortgage/.test(v) && !/půjčk|\bloan\b|úvěr/.test(v)) {
      return false;
    }
    return /\bloan\b|půjčk|půjčka|úvěr/.test(v);
  }

  function formRoot($el) {
    return $el.closest(FORM_ROOT);
  }

  function subtypeRow($form) {
    return $form
      .find('.contacts__row')
      .has('.contacts__add-mortgate, .contacts__add-loan')
      .first();
  }

  function questionSelect($form) {
    var $creditType = $form.find('select.credit-type').first();
    if ($creditType.length) {
      return $creditType;
    }

    return $form
      .find('.contacts__row select')
      .not('.contacts__add-mortgate select, .contacts__add-loan select')
      .first();
  }

  function setPanelState($panel, isActive) {
    $panel.toggleClass('active', isActive);
    $panel.find('input[type="checkbox"], input[type="radio"]').each(function () {
      this.disabled = !isActive;
      if (!isActive) {
        this.checked = false;
      }
    });
  }

  function toggleSubtypePanels($row, value) {
    if (!$row.length) {
      return;
    }

    var showMortgage = isMortgageType(value);
    var showLoan = isLoanType(value) && !showMortgage;

    setPanelState($row.find('.contacts__add-mortgate'), showMortgage);
    setPanelState($row.find('.contacts__add-loan'), showLoan);
  }

  function revealInvalidSubtypePanels($form, value) {
    var showMortgage = isMortgageType(value);
    var showLoan = isLoanType(value) && !showMortgage;

    $form.find('.contacts__add-mortgate').each(function () {
      var $panel = $(this);
      if (
        showMortgage &&
        ($panel.find('.wpcf7-not-valid, [aria-invalid="true"]').length ||
          $panel
            .find('.wpcf7-not-valid-tip')
            .filter(function () {
              return $.trim($(this).text()) !== '';
            }).length)
      ) {
        setPanelState($panel, true);
      }
    });

    $form.find('.contacts__add-loan').each(function () {
      var $panel = $(this);
      if (
        showLoan &&
        ($panel.find('.wpcf7-not-valid, [aria-invalid="true"]').length ||
          $panel
            .find('.wpcf7-not-valid-tip')
            .filter(function () {
              return $.trim($(this).text()) !== '';
            }).length)
      ) {
        setPanelState($panel, true);
      }
    });
  }

  function detachLegacyHandlers($form) {
    $form.find('select.credit-type').off('change');
  }

  function consultFormElement($root) {
    return $root.is('form') ? $root : $root.find('form').first();
  }

  function hasSubmitBeenAttempted($form) {
    return $form.hasClass(SUBMIT_ATTEMPTED);
  }

  function markSubmitAttempted($form) {
    $form.addClass(SUBMIT_ATTEMPTED);
  }

  function resetSubmitAttempted($form) {
    $form.removeClass(SUBMIT_ATTEMPTED);
    clearPrematureValidation($form);
  }

  function clearPrematureValidation($form) {
    if (!$form.length || hasSubmitBeenAttempted($form)) {
      return;
    }

    $form.find('.wpcf7-not-valid-tip').remove();
    $form.find('.wpcf7-not-valid').removeClass('wpcf7-not-valid');
    $form.find('[aria-invalid="true"]').attr('aria-invalid', 'false');
    $form.find('.wpcf7-form-control').each(function () {
      if (typeof this.setCustomValidity === 'function') {
        this.setCustomValidity('');
      }
    });
  }

  function syncSubtypePanels($form) {
    if (!$form.length) {
      return;
    }

    detachLegacyHandlers($form);

    var $row = subtypeRow($form);
    var $select = questionSelect($form);

    if ($row.length && $select.length) {
      toggleSubtypePanels($row, $select.val());
      revealInvalidSubtypePanels($form, $select.val());
    }
  }

  function initConsultForms() {
    $(FORM_ROOT).each(function () {
      var $root = $(this);
      var $form = consultFormElement($root);
      resetSubmitAttempted($form);
      syncSubtypePanels($root);
    });
  }

  $(document).on('change', FORM_ROOT + ' select.credit-type', function () {
    var $root = formRoot($(this));
    syncSubtypePanels($root);
    clearPrematureValidation(consultFormElement($root));
  });

  $(document).on(
    'change input',
    FORM_ROOT + ' .wpcf7-form-control',
    function () {
      var $form = consultFormElement(formRoot($(this)));
      if (!hasSubmitBeenAttempted($form)) {
        window.setTimeout(function () {
          clearPrematureValidation($form);
        }, 0);
      }
    }
  );

  $(document).on(
    'click',
    FORM_ROOT + ' input[type="submit"], ' + FORM_ROOT + ' button[type="submit"]',
    function () {
      markSubmitAttempted(consultFormElement(formRoot($(this))));
    }
  );

  $(document).on('click', '.callback', function () {
    $('.popup-consult .wpcf7').each(function () {
      resetSubmitAttempted(consultFormElement($(this)));
      syncSubtypePanels($(this));
    });
  });

  $(document).on('click', '.popup-consult .popup__overlay, .popup-consult .popup__close', function () {
    $('.popup-consult .wpcf7').each(function () {
      resetSubmitAttempted(consultFormElement($(this)));
    });
  });

  $(function () {
    initConsultForms();
  });

  document.addEventListener('wpcf7invalid', function (event) {
    var $root = formRoot($(event.target));
    if (!$root.length) {
      return;
    }
    var $form = consultFormElement($root);
    markSubmitAttempted($form);
    syncSubtypePanels($root);
  });

  document.addEventListener('wpcf7mailsent', function (event) {
    var $root = formRoot($(event.target));
    if ($root.length) {
      resetSubmitAttempted(consultFormElement($root));
    }
  });

  document.addEventListener('wpcf7mailfailed', function (event) {
    var $root = formRoot($(event.target));
    if ($root.length) {
      markSubmitAttempted(consultFormElement($root));
    }
  });
})(jQuery);
