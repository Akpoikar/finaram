<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractCookiePolicy;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\customize\banner\CookiePolicy as BannerCookiePolicy;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Cookie policy settings.
 * @internal
 */
class CookiePolicy extends AbstractCookiePolicy
{
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    const SYNC_OPTIONS = ['page' => ['data' => ['menu_order'], 'taxonomies' => [], 'meta' => ['copy' => [], 'copy-once' => []]]];
    /**
     * Singleton instance.
     *
     * @var CookiePolicy
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        // ...
    }
    /**
     * Register settings.
     */
    public function register()
    {
        // ...
    }
    // Documented in AbstractCookiePolicy
    public function getInstructionText()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_INSTRUCTION));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineTableOfContents()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_TABLE_OF_CONTENTS));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineControllerOfWebsite()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_CONTROLLER_OF_WEBSITE));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineDiffToPrivacyPolicy()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_DIFF_TO_PRIVACY_POLICY));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineCookieTechnology()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_COOKIE_TECHNOLOGY));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineLegalBasis()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_LEGAL_BASIS));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineRightsOfTheVisitor()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_RIGHTS_OF_THE_VISITOR));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineManageCookies()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_MANAGE_COOKIES));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineTypesOfCookies()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_TYPES_OF_COOKIES));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineCookieOrigin()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_COOKIE_ORIGIN));
    }
    // Documented in AbstractCookiePolicy
    public function getHeadlineListOfServices()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_HEADLINE_LIST_OF_SERVICES));
    }
    // Documented in AbstractCookiePolicy
    public function getContentDiffToPrivacyPolicy()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_DIFF_TO_PRIVACY_POLICY));
    }
    // Documented in AbstractCookiePolicy
    public function getContentCookieTechnology()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_COOKIE_TECHNOLOGY));
    }
    // Documented in AbstractCookiePolicy
    public function getContentLegalBasisGdpr()
    {
        $text = $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_LEGAL_BASIS_GDPR));
        if (Core::getInstance()->getCompLanguage()->isCurrentlyInEditorPreview()) {
            return $text;
        }
        $euLegalBasis = \_x('Art. 5 (3) ePrivacy Directive and Recital 66 ePrivacy Directive', 'gdpr-legal-basis', 'real-cookie-banner');
        $locale = \get_locale();
        $localeTwoLetter = \substr($locale, 0, 2);
        switch ($localeTwoLetter) {
            case 'nl':
                $euLegalBasis = \_x('Article 11.7a Dutch Telecommunications Act (Telecommunicatiewet) and Article 129 Electronic Communications Act (Wet betreffende de elektronische communicatie, Belgium)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'fi':
                $euLegalBasis = \_x('§ 205 Act on electronic communications services (Laki sähköisen viestinnän palveluista)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'fr':
                $euLegalBasis = \_x('Article 82 Data Protection Act (Loi informatique et libertés, France) and Article 129 Electronic Communications Act (Loi relative aux communications electroniques, Belgium)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'de':
                $euLegalBasis = \_x('§ 25 TDDDG (Germany) and § 165 TKG (Austria)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'el':
                $euLegalBasis = \_x('Article 4 (5) of Law 3471/2006 (Protection of personal data and privacy in the electronic communications sector and amendment of Law 2472/1997)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'pt':
                $euLegalBasis = \_x('Article 5 Electronic Communications Privacy Law (Lei da Privacidade nas Comunicações Eletrónicas)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'es':
                $euLegalBasis = \_x('Article 22 (2) Information Society Services and e-Commerce Act (LSSI, Ley de Servicios de la Sociedad de la Información y de Comercio Electrónico)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            default:
                break;
        }
        switch ($locale) {
            case 'cs_CZ':
                $euLegalBasis = \_x('§ 89 (3) Electronic Communications Act (Zákon o elektronických komunikacích)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'da_DK':
                $euLegalBasis = \_x('§ 3 Cookie Order (Cookiebekendtgørelsen)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'hu_HU':
                $euLegalBasis = \_x('§ 13/A Act on certain aspects of electronic commerce services and information society services (Törvény az elektronikus kereskedelmi szolgáltatások, valamint az információs társadalommal összefüggő szolgáltatások egyes kérdéseiről)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'it_IT':
                $euLegalBasis = \_x('Section 122 Italian personal data protection code (Codice in materia di protezione dei dati personali)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'nn_NO':
                $euLegalBasis = \_x('§ 2-7b Electronic Communications Act (Elektroniskekommunikasjonsloven)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'pl_PL':
                $euLegalBasis = \_x('§ 173 Telecommunications Act (Prawo telekomunikacyjne)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'ro_RO':
                $euLegalBasis = \_x('Article 4 Act on the Processing of Personal Data and the Protection of Privacy in the Electronic Communications Sector (LEGE privind prelucrarea datelor cu caracter personal și protecția vieții private în sectorul comunicațiilor electronice)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'sk_SK':
                $euLegalBasis = \_x('Article 55 Electronic Communications Act (zákon elektronických komunikáciách)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            case 'sv_SE':
                $euLegalBasis = \_x('Chapter 9 Section 28 Swedish Electronic Communications Act (Lag om elektronisk kommunikation)', 'gdpr-legal-basis', 'real-cookie-banner');
                break;
            default:
                break;
        }
        return \str_replace('{{euLegalBasis}}', $euLegalBasis, $text);
    }
    // Documented in AbstractCookiePolicy
    public function getContentLegalBasisDsg()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_LEGAL_BASIS_DSG));
    }
    // Documented in AbstractCookiePolicy
    public function getContentRightsOfVisitor()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_RIGHTS_OF_THE_VISITOR));
    }
    // Documented in AbstractCookiePolicy
    public function getContentManageCookies()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_MANAGE_COOKIES));
    }
    // Documented in AbstractCookiePolicy
    public function getContentTypesOfCookies()
    {
        $typesOfCookiesText = $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_TYPES_OF_COOKIES));
        $nonDefaultGroups = $this->getSettings()->getGeneral()->getNonDefaultServiceGroups();
        if (\count($nonDefaultGroups) > 0 && $this->isShowCustomGroups()) {
            $typesOfCookiesText .= \sprintf('<p>%s</p>', \sprintf(
                // translators:
                \_x('In addition, cookies can also be used by services from the following groups or for the following purposes: %s', 'legal-text', 'real-cookie-banner'),
                \join(', ', \array_map(function ($group) {
                    return $group->getName();
                }, $nonDefaultGroups))
            ));
        }
        return $typesOfCookiesText;
    }
    // Documented in AbstractCookiePolicy
    public function isShowCustomGroups()
    {
        return $this->getCustomizeSetting(BannerCookiePolicy::SETTING_SHOW_CUSTOM_GROUPS);
    }
    // Documented in AbstractCookiePolicy
    public function isTableDarkMode()
    {
        return $this->getCustomizeSetting(BannerCookiePolicy::SETTING_IS_TABLE_DARK_MODE);
    }
    // Documented in AbstractCookiePolicy
    public function getContentCookiesOrigin()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_COOKIE_ORIGIN));
    }
    // Documented in AbstractCookiePolicy
    public function getAdditionalContent()
    {
        return $this->translateString($this->getCustomizeSetting(BannerCookiePolicy::SETTING_ADDITIONAL_CONTENT));
    }
    // Documented in AbstractCookiePolicy
    public function getControllerOfWebsiteLabels()
    {
        return ['provider' => \_x('Provider', 'legal-text', 'real-cookie-banner'), 'phone' => \_x('Phone', 'legal-text', 'real-cookie-banner'), 'email' => \_x('Email', 'legal-text', 'real-cookie-banner'), 'contactForm' => \_x('Contact form', 'legal-text', 'real-cookie-banner')];
    }
    /**
     * Translate a string from the customizer texts.
     *
     * @param string $string
     * @return string
     */
    public function translateString($string)
    {
        return Core::getInstance()->getCompLanguage()->translateArray(['content' => $string])['content'];
    }
    // Documented in AbstractCookiePolicy
    public function getListOfServicesTableColumnLabels()
    {
        return ['category' => \_x('Category', 'legal-text', 'real-cookie-banner'), 'tcfVendors' => \_x('TCF vendors', 'legal-text', 'real-cookie-banner'), 'technicalCookieDefinition' => \_x('Technical cookie name', 'legal-text', 'real-cookie-banner'), 'technicalCookieHost' => \_x('Technical cookie host', 'legal-text', 'real-cookie-banner'), 'service' => \_x('Service', 'legal-text', 'real-cookie-banner'), 'purpose' => \_x('Purpose', 'legal-text', 'real-cookie-banner'), 'undefined' => '-', 'duration' => \_x('Duration', 'legal-text', 'real-cookie-banner'), 'durationUnit' => ['n1' => ['s' => \__('second', 'real-cookie-banner'), 'm' => \__('minute', 'real-cookie-banner'), 'h' => \__('hour', 'real-cookie-banner'), 'd' => \__('day', 'real-cookie-banner'), 'mo' => \__('month', 'real-cookie-banner'), 'y' => \__('year', 'real-cookie-banner')], 'nx' => ['s' => \__('seconds', 'real-cookie-banner'), 'm' => \__('minutes', 'real-cookie-banner'), 'h' => \__('hours', 'real-cookie-banner'), 'd' => \__('days', 'real-cookie-banner'), 'mo' => \__('months', 'real-cookie-banner'), 'y' => \__('years', 'real-cookie-banner')]], 'type' => \_x('Type', 'legal-text', 'real-cookie-banner')];
    }
    // Documented in AbstractCookiePolicy
    public function getGridJsLanguageTexts()
    {
        return ['search' => ['placeholder' => \__('Search...', 'real-cookie-banner')], 'sort' => ['sortAsc' => \__('Sort column ascending', 'real-cookie-banner'), 'sortDesc' => \__('Sort column descending', 'real-cookie-banner')], 'pagination' => [
            'previous' => \__('Previous', 'real-cookie-banner'),
            'next' => \__('Next', 'real-cookie-banner'),
            // translators:
            'navigate' => \__('Page %1$d of %2$d', 'real-cookie-banner'),
            // translators:
            'page' => \__('Page %d', 'real-cookie-banner'),
            'showing' => \__('Showing', 'real-cookie-banner'),
            'of' => \__('of', 'real-cookie-banner'),
            'to' => \__('to', 'real-cookie-banner'),
            'results' => \__('results', 'real-cookie-banner'),
        ], 'noRecordsFound' => \__('No matching records found', 'real-cookie-banner')];
    }
    /**
     * Add a "Cookie Policy Page" post state like "Privacy Policy Page" to the created cookie policy.
     *
     * @param string[] $post_states
     * @param WP_Post $post
     */
    public function display_post_states($post_states, $post)
    {
        if ($post->ID === \DevOwl\RealCookieBanner\settings\General::getInstance()->getCookiePolicyId()) {
            $post_states['rcb_page_for_cookie_policy'] = \__('Cookie Policy Page', 'real-cookie-banner');
        }
        return $post_states;
    }
    /**
     * Add a link to edit the cookie policy directly in the customizer.
     *
     * @param string[] $actions
     * @param WP_Post $post
     */
    public function page_row_actions($actions, $post)
    {
        if ($post->ID === \DevOwl\RealCookieBanner\settings\General::getInstance()->getCookiePolicyId()) {
            $actions['rcb_edit_for_cookie_policy'] = \sprintf('<a href="%s">%s</a>', \esc_url(\add_query_arg(['autofocus[section]' => BannerCookiePolicy::SECTION, 'return' => \wp_get_raw_referer()], \admin_url('customize.php'))), \__('Customize cookie policy content', 'real-cookie-banner'));
        }
        return $actions;
    }
    /**
     * Get the a given customize setting by ID.
     *
     * @param string $id
     */
    protected function getCustomizeSetting($id)
    {
        return Core::getInstance()->getBanner()->getCustomize()->getSetting($id);
    }
    /**
     * Get singleton instance.
     *
     * @return CookiePolicy
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\CookiePolicy() : self::$me;
    }
}
