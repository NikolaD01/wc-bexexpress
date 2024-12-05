<?php

namespace WC_BE\Http\Template;

use WC_BE\Core\Factories\RepositoryFactory;

class BexCheckout
{
    protected bool $settings;
    public function __construct() {
        $this->settings = get_option('woocommerce_bex_express_shipping_method_settings')['bex_autocomplete_fields'] === "yes";
        add_filter('woocommerce_states', [$this, 'states']);
        add_filter('woocommerce_form_field', [$this,'fields'], 10, 4);
    }
    public function states() : array
    {
        $municipalitiesRepository = RepositoryFactory::create('municipalities');
        $municipalities = $municipalitiesRepository->getAll();

        $countryCode = 'RS';
        $states[$countryCode] = [];

        foreach ($municipalities as $municipality) {
            if (isset($municipality->id, $municipality->name)) {
                $states[$countryCode][$municipality->id] = $municipality->name;
            }
        }


        return $states;
    }

    public function fields ($field, $key, $args, $value) : string
    {

        if ($key === 'billing_state') {
            if ($this->settings) {
                $field = '<p class="form-row form-row-last address-field update_totals_on_change wfacp-form-control-wrapper wfacp-col-left-half wfacp_field_required wfacp-anim-wrap validate-required validate-state" id="' . $key . '_field">';
                $field .= '<label for="' . $key . '" class="wfacp-form-control-label">' . $args['label'] . '&nbsp;<abbr class="required" title="required">*</abbr></label>';
                $field .= '<span class="woocommerce-input-wrapper">';
                $field .= '<input type="text" class="input-text wfacp-form-control" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '" placeholder="' . esc_attr($args['placeholder'] ?? 'Enter state') . '" value="' . esc_attr($value) . '" />';
                $field .= '</span>';
                $field .= '<span class="wfacp_inline_error" data-key="' . $key . '_field"></span>';
                $field .= '</p>';
            }
        }

        if ($key === 'billing_city') {
            if(!$this->settings) {
            $field = '<p class="form-row form-row-first address-field wfacp-form-control-wrapper wfacp-col-left-half wfacp_field_required validate-required" id="' . $key . '_field">';
            $field .= '<label for="' . $key . '" class="wfacp-form-control-label">' . $args['label'] . '&nbsp;<abbr class="required" title="required">*</abbr></label>';
            $field .= '<span class="woocommerce-input-wrapper">';
            $field .= '<select name="' . $key . '" id="' . $key . '" class="input-text wfacp-form-control">';
            $field .= '<option selected disabled >Izaberite područje</option>';
            $field .= '</select>';
            $field .= '</span>';
            $field .= '<span class="wfacp_inline_error" data-key="' . $key . '_field"></span>';
            $field .= '</p>';
            }
        }

        if ($key === 'billing_address_1') {
            if(!$this->settings) {
                $field = '<p class="form-row form-row-first address-field wfacp-form-control-wrapper wfacp-col-left-half wfacp_field_required validate-required" id="' . $key . '_field">';
                $field .= '<label for="' . $key . '" class="wfacp-form-control-label">' . $args['label'] . '&nbsp;<abbr class="required" title="required">*</abbr></label>';
                $field .= '<span class="woocommerce-input-wrapper">';
                $field .= '<select name="' . $key . '" id="' . $key . '" class="input-text wfacp-form-control">';
                $field .= '<option selected disabled >Izaberite ulicu</option>';
                $field .= '</select>';
                $field .= '</span>';
                $field .= '<span class="wfacp_inline_error" data-key="' . $key . '_field"></span>';
                $field .= '</p>';
            }
        }

        return $field;
    }


}