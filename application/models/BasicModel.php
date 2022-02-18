<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BasicModel extends CI_Model
{
    public function myConstruct($db_name = true)
    {
        parent::__construct();
        $this->db = $this->load->database($db_name, TRUE);
    }

    public function countWhere($table, $where)
    {
        $this->db->select('id');
        $this->db->from($table);
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        return $this->db->count_all_results();
    }

    public function getDataById($table, $id, $select = '*')
    {   
        $select !== '*' && $this->db->select($select);
        return $this->db->get_where($table, array('id' => $id))->row();
    }

    public function getOne($table, $where, $select = '*', $whereIn = [], $whereNotIn = [])
    {
        $select !== '*' && $this->db->select($select);
        if($whereIn && count($whereIn) > 0) {
            foreach ($whereIn as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        if($whereNotIn && count($whereNotIn) > 0) {
            foreach ($whereNotIn as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
        $this->db->limit(1, 0);
        return $this->db->get_where($table, $where)->row();
    }

    public function getAll($table, $select = '*', $limit = null)
    {
        $select !== '*' && $this->db->select($select);
        $limit ? $this->db->limit($limit, 0) : null;
        return $this->db->get($table);
    }

    public function getWhere($table, $where, $select = '*', $limit = null, $orders = [], $whereIn = [], $whereNotIn = [])
    {
        $select !== '*' && $this->db->select($select);
        $limit ? $this->db->limit($limit, 0) : null;
        if($orders && count($orders) > 0) {
            foreach ($orders as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if($whereIn && count($whereIn) > 0) {
            foreach ($whereIn as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        if($whereNotIn && count($whereNotIn) > 0) {
            foreach ($whereNotIn as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
        return $this->db->get_where($table, $where);
    }

    public function getWhereIn($table, $where, $select = '*', $limit = null)
    {
        $select !== '*' && $this->db->select($select);
        $limit ? $this->db->limit($limit, 0) : null;
        foreach ($where as $key => $value) {
            $this->db->where_in($key, $value);
        }
        return $this->db->get($table);
    }

    public function getGroupBy($table, $select = '*', $groupBy = [], $where = [])
    {
        $select !== '*' && $this->db->select($select);
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }

        foreach ($groupBy as $key => $value) {
            $this->db->group_by($value);
        }
        return $this->db->get($table);
    }

    public function getLike($table, $where = [], $likes = [], $select = '*', $limit = null)
    {
        $select !== '*' && $this->db->select($select);
        $limit ? $this->db->limit($limit, 0) : null;
        
        if($where && count($where) > 0) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
         
        if($likes && count($likes) > 0) {
            foreach ($likes as $key => $like) {
                $this->db->like($key, $like, 'both');
            }
        }
        return $this->db->get($table);
    }

    public function create($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function createMultiple($table, $data)
    {
        $this->db->trans_start();
        $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
        if($this->db->trans_status()) {
            $this->db->trans_commit();
            return true;
        } else {
            return $this->db->trans_rollback();
        }
    }

    public function update($table, $data, $where, $whereIn = [], $whereNotIn = [])
    {
        foreach ($where as $column => $value) {
            $this->db->where($column, $value);
        }
        if($whereIn && count($whereIn) > 0) {
            foreach ($whereIn as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        if($whereNotIn && count($whereNotIn) > 0) {
            foreach ($whereNotIn as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
        return $this->db->update($table, $data);
    }

    public function updateById($table, $data, $id, $callback = false)
    {
        $this->db->where('id', $id);
        $query = $this->db->update($table, $data);
        if($callback) {
            return $this->db->get_where($table, ['id' => $id])->row();
        } else {
            return $query;
        }
    }

    public function updateMultiple($table, $data, $column)
    {
        return $this->db->update_batch($table, $data, $column);
    }

    public function deleteById($table, $id)
    {
        return $this->db->delete($table, ['id' => $id]);
    }

    public function delete($table, $where)
    {
        return $this->db->delete($table, $where);
    }

    public function deleteMultiple($table, $column, $id)
    {
        $this->db->where_in($column, $id);
        return $this->db->delete($table) ? true : false;
    }

    public function deleteWhereIn($table, $whereIn = [], $where = [])
    {
        if($where && count($where) > 0) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if($whereIn && count($whereIn) > 0) {
            foreach ($whereIn as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }

        return $this->db->delete($table) ? true : false;
    }

    public function deleteMultipleColumn($table, $where)
    {
        foreach ($where as $column => $value) {
            $this->db->where_in($column, $aval);
        }
        return $this->db->delete($table);
    }

    public function truncate($table)
    {
        return $this->db->truncate($table);
    }

    public function getMaxValue($table, $column, $where = [])
    {

        return $where && count($where) > 0
        ? $this->db
            ->select_max($column)
            ->from($table)
            ->where($where)
            ->get()
            ->row()
            ->$column
        : $this->db
            ->select_max($column)
            ->from($table)
            ->get()
            ->row()
            ->$column;
    }

    public function getMinValue($table, $column, $where = [])
    {
        return $where && count($where) > 0
        ? $this->db
            ->select_min($column)
            ->from($table)
            ->where($where)
            ->get()
            ->row()
            ->$column
        : $this->db
            ->select_min($column)
            ->from($table)
            ->get()
            ->row()
            ->$column;
    }

    public function getLast($table)
    {
        return $this->db
            ->select('*')
            ->order_by('id', "desc")
            ->limit(1)
            ->get($table)
            ->row();
    }

    public function setStatus($table, $id, $status = 'INACTIVE')
    {
        return $this->updateById($table, ['status' => $status], $id);
    }

    public function addValueBy($table, $update = [], $where = [])
    {
        if($where && count($where) > 0) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        
        if($update && count($update) > 0) {
            foreach ($update as $key => $value) {
                $this->db->set($key, "`$key` + $value", FALSE);
            }
        }
        return $this->db->update($table);       
    }
}
