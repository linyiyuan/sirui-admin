<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" 
                 icon="el-icon-plus"
                 type="primary"
                 size="mini"
                 @click="handleAddMenuTypes"
                >添加分类
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="menuTypeTable"
                :data="list"
                style="width: 100%;"
                v-loading="listLoading" border>
        <el-table-column  sortable label="ID" prop="menu_type_id" width="200" align="center">
          <template slot-scope="scope">{{scope.row.menu_type_id}}</template>
        </el-table-column>
        <el-table-column  label="分类名" width="500" align="center">
          <template slot-scope="scope">{{scope.row.menu_type_name}}</template>
        </el-table-column>
        <el-table-column sortable label="分类状态" prop="menu_type_status" width="250" align="center">
          <template slot-scope="scope">{{scope.row.menu_type_status | menu_type_status}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="280" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="280" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button
              icon="el-icon-edit"
              type="primary"
              size="mini"
              @click="handleEditMenuTypes(scope.$index, scope.row)"
            >编辑</el-button>
             <el-button
              icon="el-icon-delete"
              type="danger"
              size="mini"
              @click="handleDeleteMenuType(scope.$index, scope.row)"
            >删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination
        background
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        layout="total, sizes,prev, pager, next,jumper"
        :current-page.sync="listQuery.cur_page"
        :page-size="listQuery.page_size"
        :page-sizes="[5,10,15]"
        :total="total">
      </el-pagination>
    </div>
    <!-- 添加/修改菜品分类 -->
     <el-dialog title="添加/修改菜品分类" :visible.sync="menuTypeDialogVisible"  width="700px" :close-on-click-modal="false">
        <el-form ref="menuTypeForm" :model="menuTypeFormData" label-width="120px" :rules="menuTypeRules">
        <el-form-item label="分类名" prop="menu_type_name">
          <el-input type="text" v-model="menuTypeFormData.menu_type_name" placeholder="请输入分类名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="menuTypeFormData.menu_type_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button  size="small" @click="menuTypeDialogVisible = false">取 消</el-button>
        <el-button  size="small" type="primary" @click="handleSendData('menuTypeForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import {menuTypeList, createMenuType, updateMenuType, deleteMenuType} from '@/api/menu/menuType'
  import {formatDate} from '@/utils/date';
  import store from '@/store'
  const defaultListQuery = {
    cur_page: 1,
    page_size: 15,
  };
  const defaultMenuTypeFormData = {
    menu_type_id: null,
    menu_type_name: null,
    menu_type_status: 1,
  }
  export default {
    name: "menuTypeList",
    components:{},
    data() {
      
      return {
        listQuery: Object.assign({}, defaultListQuery),
        listLoading: true,
        list: [],
        total: 0,
        menuTypeDialogVisible:false,
        menuTypeFormData:Object.assign({}, defaultMenuTypeFormData),
        isEdit: false,
        menuTypeRules: {
          menu_type_name: [
            { required: true, message: '请输入分类名', trigger: 'blur' }
          ],
        },
      }
    },
    created() {
      this.getList();
    },
    filters: {
      formatLoginTime(time) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
      },
      menu_type_status(menu_type_status) {
        if (menu_type_status == 1) {
          return '开启'
        }else {
          return '关闭'
        }
      }
    },
    methods: {
      handleResetSearch() {
        this.listQuery = Object.assign({}, defaultListQuery);
      },
      handleSearchList() {
        this.listQuery.cur_page = 1;
        this.getList();
      },
      handleDeleteMenuType(index, row){
        this.deleteMenuType(row.menu_type_id);
      },
      handleSizeChange(val){
        this.listQuery.cur_page = 1;
        this.listQuery.page_size = val;
        this.getList();
      },
      handleCurrentChange(val){
        this.listQuery.cur_page = val;
        this.getList();
      },
      getList() {
        this.listLoading = true;
        menuTypeList(this.listQuery).then(response => {
          this.listLoading = false;
          this.list = response.data.list;
          this.total = response.data.total;
        });
      },
      handleAddMenuTypes() {
        this.isEdit = false;
        this.menuTypeDialogVisible = true;
        this.menuTypeFormData = Object.assign({}, defaultMenuTypeFormData);
      },
      handleEditMenuTypes(index,row) {
        this.isEdit = true;
        this.menuTypeDialogVisible = true;
        this.menuTypeFormData.menu_type_id = row.menu_type_id
        this.menuTypeFormData.menu_type_name = row.menu_type_name
        this.menuTypeFormData.menu_type_status = row.menu_type_status
      },
      handleSendData(menuTypeForm) {
      this.$refs[menuTypeForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createMenuType({ postData: this.menuTypeFormData }).then(response => {
                this.$refs[menuTypeForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.menuTypeDialogVisible = false
              });
            } else if (this.isEdit) {
              updateMenuType(this.menuTypeFormData.menu_type_id, { postData: this.menuTypeFormData }).then(response => {
                this.$refs[menuTypeForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.menuTypeDialogVisible = false
              });
            }

          });
        } else {
          this.$message({
            message: '验证失败',
            type: 'error',
            duration: 1000
          });
          return false;
        }
      });
    },
      deleteMenuType(id){
        this.$confirm('是否要进行该删除操作?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          deleteMenuType(id).then(response=>{
            this.$message({
              message: '删除成功！',
              type: 'success',
              duration: 1000
            });
            this.getList();
          });
        })
      },
    }
  }
</script>
<style scoped>
  .input-width {
    width: 203px;
  }
</style>


