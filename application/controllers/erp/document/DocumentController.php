<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dhtmlx\Connector\ComboConnector;

class DocumentController extends Erp_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->Model('QhseModel');
        $this->QhseModel->myConstruct('qhse');

        $this->auth->isAuth();
    }

    public function getDepartments()
    {
        if (empRole() === 'admin' || empRank() == 1) {
            $depts = $this->Hr->getWhere('departments', ['location' => empLoc()], 'id, name')->result();
        } else {
            $depts = $this->Hr->getWhere('departments', ['location' => empLoc(), 'id' => empDept()], 'id, name')->result();
        }

        $deptList = [];
        foreach ($depts as $dept) {
            if (empRole() === 'admin' || empSub() == 0) {
                $subs = $this->Hr->getWhere('sub_departments', ['department_id' => $dept->id])->result();
            } else {
                $subs = $this->Hr->getWhere('sub_departments', ['id' => empSub()])->result();
            }
            $items = [];
            foreach ($subs as $sub) {
                $items[] = [
                    'id' => $sub->id,
                    'text' => $sub->name,
                    'icons' => [
                        'file' => 'icon_folder',
                        'folder_opened' => 'icon_department',
                        'folder_closed' => 'icon_department',
                    ],
                ];
            }
            $deptList[] = [
                'id' => 'dept-' . $dept->id,
                'text' => $dept->name,
                'icons' => [
                    'file' => 'icon_department',
                    'folder_opened' => 'icon_department',
                    'folder_closed' => 'icon_department',
                ],
                'items' => $items,
                'open' => count($items) > 0 ? 1 : 0,
            ];
        }
        response(['status' => 'success', 'depts' => $deptList]);
    }

    public function getMainFolders()
    {
        $params = getParam();
        $subId = $params['subId'];

        $combo = new ComboConnector($this->db, "PHPCI");
        $combo->render_sql("SELECT id,name FROM $this->kf_qhse.main_folders WHERE sub_department_id = '$subId' ORDER BY name ASC", 'id', 'name');
    }

    public function getTrees()
    {
        $params = getParam();
        $subId = $params['subId'];

        $subDeptIds = [];
        $subIds = [];
        $subList = [];
        $mainFileList = [];
        $subFileList = [];
        $files = [];
        $mainFolder = [];
        $subFolder = [];
        $totalSize = 0;

        $folders = $this->QhseModel->getFolders('main_folders', ['sub_department_id' => $subId]);
        foreach ($folders as $folder) {
            $subDeptIds[] = $folder->id;
            $mainFolder['main-' . $folder->id] = [
                'id' => 'main-' . $folder->id,
                'folder_name' => $folder->name,
                'total_file' => 0,
                'total_size' => 0,
                'sub_folder' => 0,
                'created_by' => $folder->emp1,
                'updated_by' => $folder->emp2,
                'created_at' => toIndoDateTime($folder->created_at),
                'updated_at' => toIndoDateTime($folder->updated_at),
            ];
        }

        if (count($subDeptIds) > 0) {
            $mainFiles = $this->QhseModel->getFiles('parent_id', $subDeptIds);
            foreach ($mainFiles as $main) {
                $mainFileList[$main->parent_id][] = [
                    'id' => 'mfile-' . $main->id,
                    'text' => $main->name,
                    'type' => $main->type,
                ];
                $files['mfile-' . $main->id] = [
                    'id' => 'mfile-' . $main->id,
                    'name' => $main->name,
                    'type' => $main->type,
                    'size' => setFileSize($main->size),
                    'filename' => $main->filename,
                    'revision' => $main->revision,
                    'effective_date' => $main->effective_date,
                    'created_by' => $main->emp1,
                    'updated_by' => $main->emp2,
                    'created_at' => toIndoDateTime($main->created_at),
                    'updated_at' => toIndoDateTime($main->updated_at),
                ];
                $totalSize += $main->size;
                $mainFolder['main-' . $main->parent_id]['total_file'] += 1;
                $mainFolder['main-' . $main->parent_id]['total_size'] += $main->size;
            }

            $subs = $this->QhseModel->getFolders('sub_folders', ['parent_id' => $subDeptIds]);
            foreach ($subs as $sub) {
                $subIds[] = $sub->id;
                $subList[$sub->parent_id][] = [
                    'id' => 'sub-' . $sub->id,
                    'text' => $sub->name,
                ];
                $mainFolder['main-' . $sub->parent_id]['sub_folder'] += 1;

                $subFolder['sub-' . $sub->id] = [
                    'id' => 'sub-' . $sub->id,
                    'folder_name' => $sub->name,
                    'total_file' => 0,
                    'total_size' => 0,
                    'sub_folder' => 0,
                    'created_by' => $sub->emp1,
                    'updated_by' => $sub->emp2,
                    'created_at' => toIndoDateTime($sub->created_at),
                    'updated_at' => toIndoDateTime($sub->updated_at),
                ];
            }
        }

        if (count($subIds) > 0) {
            $subFiles = $this->QhseModel->getFiles('sub_id', $subIds);
            foreach ($subFiles as $sub) {
                $subFileList['sub-' . $sub->sub_id][] = [
                    'id' => 'sfile-' . $sub->id,
                    'text' => $sub->name,
                    'type' => $sub->type,
                ];
                $files['sfile-' . $sub->id] = [
                    'id' => 'sfile-' . $sub->id,
                    'name' => $sub->name,
                    'type' => $sub->type,
                    'size' => setFileSize($sub->size),
                    'filename' => $sub->filename,
                    'revision' => $sub->revision,
                    'effective_date' => $sub->effective_date,
                    'created_by' => $sub->emp1,
                    'updated_by' => $sub->emp2,
                    'created_at' => toIndoDateTime($sub->created_at),
                    'updated_at' => toIndoDateTime($sub->updated_at),
                ];
                $totalSize += $sub->size;
                $mainFolder['main-' . $sub->main_id]['total_file'] += 1;
                $mainFolder['main-' . $sub->main_id]['total_size'] += $sub->size;

                $subFolder['sub-' . $sub->sub_id]['total_file'] += 1;
                $subFolder['sub-' . $sub->sub_id]['total_size'] += $sub->size;
            }
        }

        $folderList = [];
        foreach ($folders as $folder) {
            $items = [];
            $mainItems = [];
            $fileIcon = 'icon_folder_closed';

            if (array_key_exists($folder->id, $mainFileList)) {
                foreach ($mainFileList[$folder->id] as $key => $mValue) {
                    $type = $mValue['type'] == 'pdf' ? 'icon_pdf' : 'icon_word';
                    $items[] = [
                        'id' => $mValue['id'],
                        'text' => $mValue['text'],
                        'icons' => [
                            'file' => $type,
                            'folder_opened' => 'icon_opened',
                            'folder_closed' => 'icon_closed',
                        ],
                    ];
                }
            }

            if (array_key_exists($folder->id, $subList)) {
                $subItems = [];
                $fileIcon = 'icon_file';

                foreach ($subList[$folder->id] as $key => $value) {
                    if (array_key_exists($value['id'], $subFileList)) {
                        foreach ($subFileList[$value['id']] as $key => $sValue) {
                            $type = $sValue['type'] == 'pdf' ? 'icon_pdf' : 'icon_word';
                            $subItems[] = [
                                'id' => $sValue['id'],
                                'text' => $sValue['text'],
                                'icons' => [
                                    'file' => $type,
                                    'folder_opened' => 'icon_opened',
                                    'folder_closed' => 'icon_closed',
                                ],
                            ];
                        }
                    }

                    $open = count($subItems) > 0 ? 1 : 0;

                    $items[] = [
                        'id' => $value['id'],
                        'text' => $value['text'],
                        'icons' => [
                            'file' => 'icon_folder_closed',
                            'folder_opened' => 'icon_opened',
                            'folder_closed' => 'icon_closed',
                        ],
                        'items' => $subItems,
                        'open' => $open,
                    ];
                }
            }

            $open = count($items) > 0 ? 1 : 0;

            $folderList[] = [
                'id' => 'main-' . $folder->id,
                'text' => $folder->name,
                'icons' => [
                    'file' => $fileIcon,
                    'folder_opened' => 'icon_opened',
                    'folder_closed' => 'icon_closed',
                ],
                'items' => $items,
                'open' => $open,
            ];
        }

        foreach ($mainFolder as $key => $value) {
            $mainFolder[$key]['total_size'] = setFileSize($value['total_size']);
        }

        foreach ($subFolder as $key => $value) {
            $subFolder[$key]['total_size'] = setFileSize($value['total_size']);
        }

        $fileLimit = $this->Hr->getDataById('sub_departments', $subId)->file_limit;
        $isFull = $totalSize >= $fileLimit;
        response([
            'status' => 'success',
            'folders' => $folderList,
            'files' => $files,
            'main' => $mainFolder,
            'sub' => $subFolder,
            'totalSize' => setFileSize($totalSize) . ' / ' . setFileSize($fileLimit),
            'isFull' => $isFull,
        ]);
    }

    public function docForm()
    {
        $params = getParam();
        if (isset($params['id'])) {

        } else {
            $post = prettyText(getPost(), ['name']);
            $this->createFolder($post);
        }
    }

    public function createFolder($post)
    {
        $isExist = $this->Qhse->getOne('main_folders', ['name' => $post['name'], 'sub_department_id' => $post['sub_department_id']]);
        isExist(["Folder $post[name]" => $isExist]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date("Y_m_d__H_i_s");

        $insertId = $this->Qhse->create('main_folders', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function docSubForm()
    {
        $params = getParam();
        if (isset($params['id'])) {

        } else {
            $post = prettyText(getPost(), ['name']);
            $this->createSubFolder($post);
        }
    }

    public function createSubFolder($post)
    {
        $isExist = $this->Qhse->getOne('sub_folders', ['name' => $post['name'], 'parent_id' => $post['parent_id']]);
        isExist(["Folder $post[name]" => $isExist]);

        $post['created_by'] = empId();
        $post['updated_by'] = empId();
        $post['updated_at'] = date("Y_m_d__H_i_s");

        $insertId = $this->Qhse->create('sub_folders', $post);
        xmlResponse('inserted', $post['name']);
    }

    public function fileUpload()
    {
        $params = getParam();
        $save = isset($params['save']) ? $params['save'] : true;
        $this->uploadTempFile('document_control', $save);
    }

    public function tempFile()
    {
        $this->getTempFile('document_control');
    }

    public function resetForm()
    {
        $file = $this->Main->getOne('temp_files', ['emp_id' => empId(), 'action' => 'document_control']);
        if ($file) {
            if (file_exists('./assets/files/' . $file->filename)) {
                unlink('./assets/files/' . $file->filename);
            }
            $this->Main->delete('temp_files', ['emp_id' => empId(), 'action' => 'document_control']);
            response(['status' => 'success']);
        }
    }

    public function createFile()
    {
        $params = getParam();
        $post = prettyText(getPost(), ['name']);
        $folder = explode('-', $post['folder_id']);
        $path = $folder[0];
        $id = $folder[1];
        $subId = $post['subId'];

        $isFull = $this->QhseModel->getFreeSpace($subId);
        if ($isFull) {
            xmlResponse('full', "Memory Full, Silahkan hubungi admin!");
        }

        $column = $path == 'main' ? 'parent_id' : 'sub_id';
        $isExist = $this->Qhse->getOne('files', [$column => $id, 'name' => $post['name']]);
        isExist(["Nama file $post[name]" => $isExist]);

        $file = $this->Main->getOne('temp_files', ['filename' => $post['filename']]);
        $data = [
            'name' => $post['name'],
            'type' => $file->type,
            'size' => $file->size,
            'filename' => $file->filename,
            'effective_date' => $post['effective_date'],
            'revision' => $post['revision'],
            'created_by' => empId(),
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $data['parent_id'] = $path == 'main' ? $id : 0;
        $data['sub_id'] = $path == 'sub' ? $id : 0;

        $insertId = $this->Qhse->create('files', $data);
        $revision = [
            'file_id' => $insertId,
            'sub_id' => $subId,
            'name' => $post['name'],
            'revision' => $post['revision'],
            'revised_by' => empId(),
            'type' => $file->type,
            'size' => $file->size,
            'filename' => $file->filename,
            'revision_date' => date('Y-m-d'),
            'remark' => 'First Upload',
        ];

        if ($insertId) {
            $this->Qhse->create("file_revisions", $revision);
            $this->Main->delete('temp_files', ['filename' => $post['filename']]);
            xmlResponse('inserted', $post['name']);
        } else {
            xmlResponse('error', $post['name']);
        }
    }

    public function checkBeforeRevision()
    {
        $post = fileGetContent();
        $id = explode('-', $post->id)[1];
        $file = $this->Qhse->getDataById('files', $id);
        if ($file->name !== $post->name) {
            $column = $file->parent_id === 1 ? 'parent_id' : 'sub_id';
            $value = $file->parent_id === 1 ? $file->parent_id : $file->sub_id;
            $isExist = $this->Qhse->getOne('files', [$column => $value, 'name' => $post->name]);
            if ($isExist) {
                response(['status' => 'exist', 'message' => "Nama file $post->name sudah digunakan!"]);
            } else {
                response(['status' => 'success']);
            }
        } else {
            response(['status' => 'success']);
        }
    }

    public function revisionFile()
    {
        $params = getParam();
        $post = prettyText(getPost(), ['name']);
        $id = explode('-', $post['id'])[1];

        $subId = $this->Qhse->getOne('file_revisions', ['file_id' => $id], 'sub_id')->sub_id;
        $isFull = $this->QhseModel->getFreeSpace($subId);
        if ($isFull) {
            xmlResponse('full', 'Memory Full, Silahkan hubungi admin!');
        }

        $file = $this->Main->getOne('temp_files', ['filename' => $post['filename']]);

        $updateFile = [
            'name' => $post['name'],
            'type' => $file->type,
            'size' => $file->size,
            'filename' => $post['filename'],
            'effective_date' => $post['effective_date'],
            'revision' => $post['revision'],
            'updated_by' => empId(),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $revision = [
            'file_id' => $id,
            'sub_id' => $subId,
            'name' => $post['name'],
            'revision' => $post['revision'],
            'revised_by' => empId(),
            'type' => $file->type,
            'size' => $file->size,
            'filename' => $post['filename'],
            'revision_date' => date('Y-m-d'),
            'remark' => $post['remark'],
        ];

        $this->Qhse->updateById('files', $updateFile, $id);
        $this->Qhse->create('file_revisions', $revision);
        $this->Main->delete('temp_files', ['filename' => $file->filename]);
        xmlResponse('inserted', $post['name']);
    }

    public function fileGrid()
    {
        $params = getParam();
        $id = explode('-', $params['fileId'])[1];
        $revisions = $this->QhseModel->getRevisions($id);

        $xml = "";
        $no = 1;
        foreach ($revisions as $rev) {
            $xml .= "<row id='$rev->id'>";
            $xml .= "<cell>". cleanSC($no) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->name) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->filename) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->type) ."</cell>";
            $xml .= "<cell>". cleanSC(setFileSize($rev->size)) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->remark) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->revision) ."</cell>";
            $xml .= "<cell>". cleanSC($rev->revision_by) ."</cell>";
            $xml .= "<cell>". cleanSC(toIndoDate($rev->revision_date)) ."</cell>";
            $xml .= "</row>";
            $no++;
        }
        gridXmlHeader($xml);
    }

    public function deleteDoc()
    {
        $post = fileGetContent();
        $fullId = explode('-', $post->id);
        $id = $fullId[1];
        $path = $fullId[0];

        if ($path === 'mfile' || $path === 'sfile') {
            $file = $this->Qhse->getDataById('files', $id);
            $files = $this->Qhse->getWhere('file_revisions', ['file_id' => $id])->result();
            foreach ($files as $file) {
                if (file_exists('./assets/files/' . $file->filename)) {
                    unlink('./assets/files/' . $file->filename);
                }
            }
            $this->Qhse->deleteById('files', $id);
            $this->Qhse->delete('file_revisions', ['file_id' => $id]);
            response(['status' => 'success', 'message' => "Dokumen $file->name beserta revisinya telah dihapus!"]);
        } else {
            $table = $path === 'main' ? 'main_folders' : 'sub_folders';
            if ($table === 'main_folders') {
                $folder = $this->Qhse->getDataById('main_folders', $id);
                $checkSub = $this->Qhse->getOne('sub_folders', ['parent_id' => $id]);
                $checkFile = $this->Qhse->getOne('files', ['parent_id' => $id]);
                if ($checkSub || $checkFile) {
                    response(['status' => 'error', 'message' => "Folder $folder->name tidak dapat dihapus, hapus semua sub folder & file dari $folder->name terlebih dahulu!"]);
                }
                $this->Qhse->deleteById('main_folders', $id);
                response(['status' => 'success', 'message' => "Folder $folder->name berhasil dihapus!"]);
            } else {
                $folder = $this->Qhse->getDataById('sub_folders', $id);
                $checkFile = $this->Qhse->getOne('files', ['sub_id' => $id]);
                if ($checkFile) {
                    response(['status' => 'error', 'message' => "Folder $folder->name tidak dapat dihapus, hapus file dari $folder->name terlebih dahulu!"]);
                }
                $this->Qhse->deleteById('sub_folders', $id);
                response(['status' => 'success', 'message' => "Folder $folder->name berhasil dihapus!"]);
            }
        }
    }

    public function deleteRevision()
    {
        $post = fileGetContent();
        $id = $post->id;
        $revision = $this->Qhse->getDataById('file_revisions', $id);
        $checkFile = $this->Qhse->getOne('files', ['filename' => $revision->filename]);
        if (!$checkFile) {
            if (file_exists('./assets/files/' . $revision->filename)) {
                unlink('./assets/files/' . $revision->filename);
            }
            $this->Qhse->deleteById('file_revisions', $id);
            response(['status' => 'success', 'message' => "Dokumen $revision->name revisi ke $revision->revision telah dihapus!"]);
        } else {
            response(['status' => 'error', 'message' => "Dokumen $revision->name adalah dokumen Aktif, tidak bisa dihapus dari menu revisi!"]);
        }
    }

    public function renameFolder()
    {
        $post = prettyText(getPost(), ['name']);
        $fdr = explode('-', $post['id']);
        $path = $fdr[0];
        $id = $fdr[1];
        $table = $path === 'main' ? 'main_folders' : 'sub_folders';

        $folder = $this->Qhse->getDataById($table, $id);
        if ($folder->name !== $post['name']) {
            $isExist = $this->Qhse->getOne($table, ['name' => $post['name']]);
            isExist(["Nama folder $post[name]" => $isExist]);
        }

        $data = ['name' => $post['name']];
        $this->Qhse->updateById($table, $data, $id);
        xmlResponse('updated', $post['name']);
    }
}
