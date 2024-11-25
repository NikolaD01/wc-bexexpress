<?php

namespace WC_BE\Http\Template;

use WC_BE\Core\Factories\RepositoryFactory;

class BexCheckout
{
    public function __construct() {
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
        if ($key === 'billing_city') {

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

        if ($key === 'billing_address_1') {
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

        return $field;
    }


}