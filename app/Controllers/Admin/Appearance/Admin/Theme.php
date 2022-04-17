<?php

namespace App\Controllers\Admin\Appearance\Admin;

class Theme extends \App\Controllers\BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Models
        $this->model_extension_extension = new \App\Models\Admin\Extension\Extension_Model();
        $this->model_system_setting = new \App\Models\Admin\System\Setting_Model();
    }

    public function index()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        $data['breadcrumbs'][] = array(
            'text' => lang('Text.dashboard'),
            'href' => $this->url->administratorLink('admin/common/dashboard'),
            'active' => false,
        );

        $data['breadcrumbs'][] = array(
            'text' => lang('Text.admin_themes'),
            'href' => $this->url->administratorLink('admin/appearance/admin/theme'),
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

        $data['heading_title'] = lang('Heading.themes');

        // Get admin themes
        $path = ROOTPATH . '/theme_admin/*/*/Controllers/Admin/Appearance/Admin/Theme/*/*';

        $themes = array_diff(glob($path), array('.', '..'));

        foreach ($themes as $theme) {
            $segment = explode('/', $theme);
            $total_segments = count($segment);

            $theme_author = $segment[$total_segments - 2];
            $theme_name = pathinfo($segment[$total_segments - 1], PATHINFO_FILENAME);

            // Check if it is installed
            $extension_info = $this->model_extension_extension->getInstalledExtension('theme_admin', $theme_author . ':' . $theme_name);

            if ($extension_info) {
                $activated = true;

                $info = file_get_contents($this->url->administratorLink('admin/appearance/admin/theme/' . strtolower($theme_author) . '/' . strtolower($theme_name) . '/get_info'));

                $theme_info = json_decode($info, true);

                $image = $theme_info['image'];

                if (!empty($theme_info['link'])) {
                    $link = $theme_info['link'];
                } else {
                    $link = false;
                }

                if (!empty($theme_info['description'])) {
                    $description = $theme_info['description'];
                } else {
                    $description = false;
                }

                if (!empty($theme_info['image'])) {
                    $image = $theme_info['image'];
                } else {
                    $image = false;
                }
            } else {
                $activated = false;

                $link = false;

                $description = lang('Text.theme_not_installed_description');

                $image = base_url() . '/assets/images/theme_not_installed.png';
            }

            $data['themes'][] = [
                'path' => $theme,
                'image' => $image,
                'theme_author' => $theme_author,
                'theme_name' => $theme_name,
                'theme_link' => $link,
                'theme_description' => $description,
                'theme_image' => $image,
                'link' => $link,
                'href' => $this->url->administratorLink('admin/appearance/admin/theme/' . strtolower($theme_author) . '/' . strtolower($theme_name)),
                'activated' => $activated,
                'activate' => base_url() . '/admin/appearance/admin/theme/activate?administrator_token=' . $this->administrator->getToken() . '&theme=' . $theme_author . ':' . $theme_name,
                'deactivate' => base_url() . '/admin/appearance/admin/theme/deactivate?administrator_token=' . $this->administrator->getToken() . '&theme=' . $theme_author . ':' . $theme_name,
                'set' => base_url() . '/admin/appearance/admin/theme/set_admin_theme?administrator_token=' . $this->administrator->getToken() . '&theme=' . $theme_author . ':' . $theme_name,
                'remove' => base_url() . '/admin/appearance/admin/theme/remove?administrator_token=' . $this->administrator->getToken() . '&theme=' . $theme_author . ':' . $theme_name,
            ];
        }

        $data['current_theme'] = $this->setting->get('setting_admin_theme');

        $data['administrator_token'] = $this->administrator->getToken();

        if ($this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            // Header
            $header_params = array(
                'title' => lang('Heading.themes'),
            );
            $data['header'] = $this->admin_header->index($header_params);
            // Column Left
            $column_left_params = array();
            $data['column_left'] = $this->admin_column_left->index($column_left_params);
            // Footer
            $footer_params = array();
            $data['footer'] = $this->admin_footer->index($footer_params);

            return $this->template->render('ThemeAdmin', 'com_openmvm', 'Basic', 'Appearance\Admin\theme', $data);
        } else {
            $data = [];

            $data['breadcrumbs'][] = array(
                'text' => lang('Text.dashboard'),
                'href' => $this->url->administratorLink('admin/common/dashboard'),
                'active' => false,
            );

            $data['breadcrumbs'][] = array(
                'text' => lang('Text.admin_themes'),
                'href' => $this->url->administratorLink('admin/appearance/admin/theme'),
                'active' => true,
            );

            $data['heading_title'] = lang('Heading.themes');

            $data['code_number'] = 403;
            $data['code_text'] = lang('Text.forbidden');

            $data['message'] = lang('Error.access_permission');

            // Header
            $header_params = [
                'title' => lang('Heading.themes'),
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

    public function upload()
    {
        $json = [];

        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            $json['error'] = lang('Error.login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $json['error'] = lang('Error.access_permission');
        }

        if (!$this->request->getFile('file')) {
            $json['error'] = lang('Error.file');
        }

        $file = new \CodeIgniter\Files\File($this->request->getFile('file'));

        if ($file->getMimeType() != 'application/zip') {
            $json['error'] = lang('Error.file_type');
        }

        if (empty($json['error'])) {
            $uploaded_file = $this->request->getFile('file');

            if ($uploaded_file->isValid() && !$uploaded_file->hasMoved()) {
                $newName = $uploaded_file->getRandomName();

                // Upload
                $uploaded_file->move(ROOTPATH . '/writable/uploads/', $newName);

                // Extract
                $file = ROOTPATH . '/writable/uploads/' . $newName;
                $destination = ROOTPATH . '/theme_admin/';
    
                $this->zip->extractTo($file, $destination, true);
    
                $json['success'] = lang('Success.upload_file');
            } else {
                $json['error'] = $uploaded_file->getErrorString() . ' ( ' . $uploaded_file->getError() . ' )';
            }
        }

        return $this->response->setJSON($json);
    }

    public function set_admin_theme()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $error = true;

            $this->session->set('error', lang('Error.access_permission'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($this->request->getGet('theme'))) {
            $error = true;

            $this->session->set('error', lang('Error.missing_parameters'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($error)) {
            $query = $this->model_system_setting->editSettingValue('setting', 'setting_admin_theme', $this->request->getGet('theme'));

            $this->session->set('success', lang('Success.theme_applied'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }
    }

    public function activate()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $error = true;

            $this->session->set('error', lang('Error.access_permission'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($this->request->getGet('theme'))) {
            $error = true;

            $this->session->set('error', lang('Error.missing_parameters'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($error)) {
            // Copy assets files
            $theme = explode(':', $this->request->getGet('theme'));

            $asset = ROOTPATH . 'theme_admin/' . $theme[0] . '/' . $theme[1] . '/assets';
            $destination = ROOTPATH . 'public/assets';

            if (is_dir($asset)) {
                $this->file->recursiveCopy($asset, $destination);
            }

            $query = $this->model_extension_extension->installExtension('theme_admin', $this->request->getGet('theme'));

            $this->session->set('success', lang('Success.theme_activated'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }
    }

    public function deactivate()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $error = true;

            $this->session->set('error', lang('Error.access_permission'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($this->request->getGet('theme'))) {
            $error = true;

            $this->session->set('error', lang('Error.missing_parameters'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($error)) {
            // Delete installed assets files
            $theme = explode(':', $this->request->getGet('theme'));

            $asset = ROOTPATH . 'public/assets/admin/theme/' . $theme[0];

            delete_files($asset, true);

            rmdir($asset);

            $query = $this->model_extension_extension->uninstallExtension('theme_admin', $this->request->getGet('theme'));

            $this->session->set('success', lang('Success.theme_deactivated'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }
    }

    public function update()
    {
        $json = [];

        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            $json['error'] = lang('Error.login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $json['error'] = lang('Error.access_permission');
        }

        if (!$this->request->getFile('file')) {
            $json['error'] = lang('Error.file');
        }

        $file = new \CodeIgniter\Files\File($this->request->getFile('file'));

        if ($file->getMimeType() != 'application/zip') {
            $json['error'] = lang('Error.file_type');
        }

        if (empty($this->request->getGet('theme'))) {
            $json['error'] = lang('Error.missing_parameters');
        }

        if (empty($json['error'])) {
            $uploaded_file = $this->request->getFile('file');

            if ($uploaded_file->isValid() && !$uploaded_file->hasMoved()) {
                $newName = $uploaded_file->getRandomName();

                // Upload
                $uploaded_file->move(ROOTPATH . '/writable/uploads/', $newName);

                // Extract
                $file = ROOTPATH . '/writable/uploads/' . $newName;
                $destination = ROOTPATH . '/theme_admin/';
    
                $this->zip->extractTo($file, $destination, true);
        
                // Copy assets files
                $theme = explode(':', $this->request->getGet('theme'));

                $asset = ROOTPATH . 'theme_admin/' . $theme[0] . '/' . $theme[1] . '/assets';
                $destination = ROOTPATH . 'public/assets';

                if (is_dir($asset)) {
                    $this->file->recursiveCopy($asset, $destination);
                }

                $json['success'] = lang('Success.upload_file');
            } else {
                $json['error'] = $uploaded_file->getErrorString() . ' ( ' . $uploaded_file->getError() . ' )';
            }
        }

        return $this->response->setJSON($json);
    }

    public function remove()
    {
        if (!$this->administrator->isLoggedIn() || !$this->administrator->verifyToken($this->request->getGet('administrator_token'))) {
            return redirect()->to('admin/administrator/login');
        }

        if (!$this->administrator->hasPermission('access', 'Appearance/Admin/Theme')) {
            $error = true;

            $this->session->set('error', lang('Error.access_permission'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($this->request->getGet('theme'))) {
            $error = true;

            $this->session->set('error', lang('Error.missing_parameters'));

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }

        if (empty($error)) {
            // Delete installed theme files
            $theme = explode(':', $this->request->getGet('theme'));

            $asset = ROOTPATH . 'theme_admin/' . $theme[0] . '/' . $theme[1];

            delete_files($asset, true);

            rmdir($asset);

            return redirect()->to('admin/appearance/admin/theme?administrator_token=' . $this->request->getGet('administrator_token'));
        }
    }
}
