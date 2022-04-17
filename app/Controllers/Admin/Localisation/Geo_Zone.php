<?php

namespace App\Controllers\Admin\Localisation;

class Geo_Zone extends \App\Controllers\BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->model_localisation_geo_zone = new \App\Models\Admin\Localisation\Geo_Zone_Model();
        $this->model_localisation_country = new \App\Models\Admin\Localisation\Country_Model();
    }

    public function index()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        $data['action'] = $this->url->administratorLink('admin/localisation/geo_zone');

        if ($this->request->getMethod() == 'post' && !empty($this->request->getPost('selected'))) {
            if (!$this->administrator->hasPermission('modify', 'Localisation/Geo_Zone')) {
                $this->session->set('error', lang('Error.modify_permission'));

                return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone'));
            }

            foreach ($this->request->getPost('selected') as $geo_zone_id) {
                // Query
                $query = $this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
            }

            $this->session->set('success', lang('Success.geo_zone_delete'));

            return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone'));
        }

        return $this->get_list($data);
    }

    public function add()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        $data['sub_title'] = lang('Heading.add');

        $data['action'] = $this->url->administratorLink('admin/localisation/geo_zone/add');

        if ($this->request->getMethod() == 'post') {
            if (!$this->administrator->hasPermission('modify', 'Localisation/Geo_Zone')) {
                $this->session->set('error', lang('Error.modify_permission'));

                return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone/add'));
            }

            $this->validation->setRule('name', lang('Entry.name'), 'required');

            if ($this->validation->withRequest($this->request)->run()) {
                // Query
                $query = $this->model_localisation_geo_zone->addGeoZone($this->request->getPost());

                $this->session->set('success', lang('Success.geo_zone_add'));

                return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone'));
            } else {
                // Errors
                $this->session->set('error', lang('Error.form'));

                if ($this->validation->hasError('name')) {
                    $data['error_name'] = $this->validation->getError('name');
                } else {
                    $data['error_name'] = '';
                }
            }
        }

        return $this->get_form($data);
    }

    public function edit()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        $data['sub_title'] = lang('Heading.edit');

        $data['action'] = $this->url->administratorLink('admin/localisation/geo_zone/edit/' . $this->uri->getSegment($this->uri->getTotalSegments()));

        if ($this->request->getMethod() == 'post') {
            if (!$this->administrator->hasPermission('modify', 'Localisation/Geo_Zone')) {
                $this->session->set('error', lang('Error.modify_permission'));

                return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone/edit/' . $this->uri->getSegment($this->uri->getTotalSegments())));
            }

            $this->validation->setRule('name', lang('Entry.name'), 'required');

            if ($this->validation->withRequest($this->request)->run()) {
                // Query
                $query = $this->model_localisation_geo_zone->editGeoZone($this->uri->getSegment($this->uri->getTotalSegments()), $this->request->getPost());

                $this->session->set('success', lang('Success.geo_zone_edit'));

                return redirect()->to($this->url->administratorLink('admin/localisation/geo_zone'));
            } else {
                // Errors
                $this->session->set('error', lang('Error.form'));

                if ($this->validation->hasError('name')) {
                    $data['error_name'] = $this->validation->getError('name');
                } else {
                    $data['error_name'] = '';
                }
            }
        }

        return $this->get_form($data);
    }

    public function get_list($data)
    {
        $data['breadcrumbs'][] = array(
            'text' => lang('Text.dashboard'),
            'href' => $this->url->administratorLink('admin/common/dashboard'),
            'active' => false,
        );

        $data['breadcrumbs'][] = array(
            'text' => lang('Text.geo_zones'),
            'href' => $this->url->administratorLink('admin/localisation/geo_zone'),
            'active' => true,
        );

        if ($this->session->has('error')) {
            $data['error_warning'] = $this->session->get('error');

            $this->session->remove('error');
        } else {
            $data['error_warning'] = '';
        }

        if ($this->session->has('success')) {
            $data['success'] = $this->session->get('success');

            $this->session->remove('success');
        } else {
            $data['success'] = '';
        }

        $data['heading_title'] = lang('Heading.geo_zones');

        // Get geo zones
        $data['geo_zones'] = [];

        $geo_zones = $this->model_localisation_geo_zone->getGeoZones();

        foreach ($geo_zones as $geo_zone) {
            $data['geo_zones'][] = [
                'geo_zone_id' => $geo_zone['geo_zone_id'],
                'name' => $geo_zone['name'],
                'href' => $this->url->administratorLink('admin/localisation/geo_zone/edit/' . $geo_zone['geo_zone_id']),
            ];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = [];
        }

        $data['add'] = $this->url->administratorLink('admin/localisation/geo_zone/add');
        $data['cancel'] = $this->url->administratorLink('admin/common/dashboard');
		
        if ($this->administrator->hasPermission('access', 'Localisation/Geo_Zone')) {
            // Header
            $header_params = array(
                'title' => lang('Heading.geo_zones'),
            );
            $data['header'] = $this->admin_header->index($header_params);
            // Column Left
            $column_left_params = array();
            $data['column_left'] = $this->admin_column_left->index($column_left_params);
            // Footer
            $footer_params = array();
            $data['footer'] = $this->admin_footer->index($footer_params);

            return $this->template->render('ThemeAdmin', 'com_openmvm', 'Basic', 'Localisation\geo_zone_list', $data);
        } else {
            $data = [];

            $data['breadcrumbs'][] = array(
                'text' => lang('Text.dashboard'),
                'href' => $this->url->administratorLink('admin/common/dashboard'),
                'active' => false,
            );

            $data['breadcrumbs'][] = array(
                'text' => lang('Text.geo_zones'),
                'href' => $this->url->administratorLink('admin/localisation/geo_zone'),
                'active' => true,
            );
    
            $data['heading_title'] = lang('Heading.geo_zones');

            $data['code_number'] = 403;
            $data['code_text'] = lang('Text.forbidden');

            $data['message'] = lang('Error.access_permission');

            // Header
            $header_params = [
                'title' => lang('Heading.geo_zones'),
            ];
            $data['header'] = $this->admin_header->index($header_params);
            // Column Left
            $column_left_params = [];
            $data['column_left'] = $this->admin_column_left->index($column_left_params);
            // Footer
            $footer_params = [];
            $data['footer'] = $this->admin_footer->index($footer_params);

            return $this->template->render('ThemeAdmin', 'com_openmvm', 'Basic', 'Common\permission', $data);
        }
    }

    public function get_form($data)
    {
        $data['breadcrumbs'][] = array(
            'text' => lang('Text.dashboard'),
            'href' => $this->url->administratorLink('admin/common/dashboard'),
            'active' => false,
        );

        $data['breadcrumbs'][] = array(
            'text' => lang('Text.geo_zones'),
            'href' => $this->url->administratorLink('admin/localisation/geo_zone'),
            'active' => false,
        );

        if ($this->uri->getSegment($this->uri->getTotalSegments() - 1) == 'edit') {
            $data['breadcrumbs'][] = array(
                'text' => lang('Text.edit'),
                'href' => '',
                'active' => true,
            );
            
            $geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->uri->getSegment($this->uri->getTotalSegments()));
        } else {
            $data['breadcrumbs'][] = array(
                'text' => lang('Text.add'),
                'href' => '',
                'active' => true,
            );

            $geo_zone_info = [];
        }

        if ($this->session->has('error')) {
            $data['error_warning'] = $this->session->get('error');

            $this->session->remove('error');
        } else {
            $data['error_warning'] = '';
        }

        if ($this->session->has('success')) {
            $data['success'] = $this->session->get('success');

            $this->session->remove('success');
        } else {
            $data['success'] = '';
        }

        $data['heading_title'] = lang('Heading.geo_zones');

        if ($this->request->getPost('name')) {
            $data['name'] = $this->request->getPost('name');
        } elseif ($geo_zone_info) {
            $data['name'] = $geo_zone_info['name'];
        } else {
            $data['name'] = '';
        }

        if ($this->request->getPost('description')) {
            $data['description'] = $this->request->getPost('description');
        } elseif ($geo_zone_info) {
            $data['description'] = $geo_zone_info['description'];
        } else {
            $data['description'] = '';
        }

        if ($this->request->getPost('zone_to_geo_zone')) {
            $data['zone_to_geo_zones'] = $this->request->getPost('zone_to_geo_zone');
        } elseif ($geo_zone_info) {
            $data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($geo_zone_info['geo_zone_id']);
        } else {
            $data['zone_to_geo_zones'] = [];
        }

        $data['countries'] = $this->model_localisation_country->getCountries();
        $data['country_request'] = $this->url->administratorLink('admin/localisation/country/get_country');

        $data['validation'] = $this->validation;
        
        $data['cancel'] = $this->url->administratorLink('admin/localisation/geo_zone');

        if ($this->administrator->hasPermission('access', 'Localisation/Geo_Zone')) {
            // Header
            $header_params = array(
                'title' => lang('Heading.geo_zones'),
            );
            $data['header'] = $this->admin_header->index($header_params);
            // Column Left
            $column_left_params = array();
            $data['column_left'] = $this->admin_column_left->index($column_left_params);
            // Footer
            $footer_params = array();
            $data['footer'] = $this->admin_footer->index($footer_params);

            return $this->template->render('ThemeAdmin', 'com_openmvm', 'Basic', 'Localisation\geo_zone_form', $data);
        } else {
            $data = [];

            $data['breadcrumbs'][] = array(
                'text' => lang('Text.dashboard'),
                'href' => $this->url->administratorLink('admin/common/dashboard'),
                'active' => false,
            );
    
            $data['breadcrumbs'][] = array(
                'text' => lang('Text.geo_zones'),
                'href' => $this->url->administratorLink('admin/localisation/geo_zone'),
                'active' => false,
            );
    
            if ($this->uri->getSegment($this->uri->getTotalSegments() - 1) == 'edit') {
                $data['breadcrumbs'][] = array(
                    'text' => lang('Text.edit'),
                    'href' => '',
                    'active' => true,
                );
            } else {
                $data['breadcrumbs'][] = array(
                    'text' => lang('Text.add'),
                    'href' => '',
                    'active' => true,
                );
            }
    
            $data['heading_title'] = lang('Heading.geo_zones');

            $data['code_number'] = 403;
            $data['code_text'] = lang('Text.forbidden');

            $data['message'] = lang('Error.access_permission');

            // Header
            $header_params = [
                'title' => lang('Heading.geo_zones'),
            ];
            $data['header'] = $this->admin_header->index($header_params);
            // Column Left
            $column_left_params = [];
            $data['column_left'] = $this->admin_column_left->index($column_left_params);
            // Footer
            $footer_params = [];
            $data['footer'] = $this->admin_footer->index($footer_params);

            return $this->template->render('ThemeAdmin', 'com_openmvm', 'Basic', 'Common\permission', $data);
        }
    }
}
