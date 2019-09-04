<template> 
  <div class="app-container">
    <el-card class="filter-container" shadow="never">
      <div>
        <i class="el-icon-search"></i>
        <span>筛选搜索</span>
        <el-button style="float:right" type="primary" @click="handleSearchList()" size="small">
          查询搜索
        </el-button>
        <el-button style="float:right;margin-right: 15px" @click="handleResetSearch()" size="small">
          重置
        </el-button>
      </div>
      <div style="margin-top: 15px">
        <el-form :inline="true" :model="listQuery" size="small">
          <el-form-item label="餐馆选择：">
            <el-select v-model="listQuery.restaurant_id" filterable placeholder="请选择餐馆">
              <el-option v-for="item in restaurantSearchList" :key="item.value" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddMenus">添加菜品
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="menuTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="ID" prop="menuname" width="200" align="center">
          <template slot-scope="scope">{{scope.row.menu_id}}</template>
        </el-table-column>
        <el-table-column label="菜品名"  align="center">
          <template slot-scope="scope">{{scope.row.menu_name}}</template>
        </el-table-column>
        <el-table-column sortable label="价格" width="120" prop="menu_amount" align="center">
          <template slot-scope="scope">{{scope.row.menu_amount}}</template>
        </el-table-column>
        <el-table-column  sortable label="分类" width="180" prop="menu_type_id"align="center">
          <template slot-scope="scope">{{scope.row.menu_type_name}}</template>
        </el-table-column>
        <el-table-column label="餐馆" width="180" align="center">
          <template slot-scope="scope">{{scope.row.restaurant_name}}</template>
        </el-table-column>
        <el-table-column sortable label="菜品状态" prop="menu_status" width="150" align="center">
          <template slot-scope="scope">{{scope.row.menu_status | menu_status}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="220" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="220" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditMenus(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteMenu(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>
    <!-- 添加/修改菜品 -->
    <el-dialog title="添加/修改菜品" :visible.sync="menuDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="menuForm" :model="menuFormData" label-width="120px" :rules="menuRules">
        <el-form-item label="餐馆选择：" prop="restaurant_id">
            <el-select v-model="menuFormData.restaurant_id" filterable placeholder="请选择餐馆">
              <el-option v-for="item in restaurantSearchList" :key="item.value" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="菜品分类" prop="menu_type_id">
            <el-select v-model="menuFormData.menu_type_id" filterable placeholder="请选择分类">
              <el-option v-for="item in menuTypeSearchList" :key="item.value" :label="item.label" :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
        <el-form-item label="菜品名" prop="menu_name">
          <el-input type="text" v-model="menuFormData.menu_name" placeholder="请输入菜品名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="价格" prop="menu_amount">
          <el-input type="text" v-model="menuFormData.menu_amount" placeholder="请输入价格" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="menuFormData.menu_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="menuDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendData('menuForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { menuList, createMenu, updateMenu, deleteMenu } from '@/api/menu/menu'
import { restaurantList } from '@/api/menu/restaurant'
import { menuTypeList } from '@/api/menu/menuType'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
};
const defaultMenuFormData = {
  menu_id: null,
  menu_name: null,
  menu_status: 1,
  menu_type_id: null,
  restaurant_id: null,
}
export default {
  name: "menuList",
  components: {},
  data() {

    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: [],
      restaurantSearchList: [],
      menuTypeSearchList: [],
      total: 0,
      menuDialogVisible: false,
      menuFormData: Object.assign({}, defaultMenuFormData),
      isEdit: false,
      menuRules: {
        menu_type_id: [
          { required: true, message: '请选择菜品分类', trigger: 'blur' }
        ],
        restaurant_id: [
          { required: true, message: '请选择餐馆分类', trigger: 'blur' }
        ],
        menu_name: [
          { required: true, message: '请输入菜品名', trigger: 'blur' }
        ],
        menu_amount: [
          { required: true, message: '请输入菜品价格', trigger: 'blur' }
        ],
      },
    }
  },
  created() {
    this.getList();
    this.getRestaurantSearchList();
    this.getMenuTypeSearchList();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
    menu_status(menu_status) {
      if (menu_status == 1) {
        return '开启'
      } else {
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
    handleDeleteMenu(index, row) {
      this.deleteMenu(row.menu_id);
    },
    handleSizeChange(val) {
      this.listQuery.cur_page = 1;
      this.listQuery.page_size = val;
      this.getList();
    },
    handleCurrentChange(val) {
      this.listQuery.cur_page = val;
      this.getList();
    },
    getList() {
      this.listLoading = true;
      menuList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    getRestaurantSearchList() {
      restaurantList({ type: 'search' }).then(response => {
        this.restaurantSearchList = response.data.list;
      });
    },
    getMenuTypeSearchList() {
      menuTypeList({ type: 'search' }).then(response => {
        this.menuTypeSearchList = response.data.list;
      });
    },
    handleAddMenus() {
      this.isEdit = false;
      this.menuDialogVisible = true;
      this.menuFormData = Object.assign({}, defaultMenuFormData);
      this.menuFormData.restaurant_id = this.listQuery.restaurant_id
    },
    handleEditMenus(index, row) {
      this.isEdit = true;
      this.menuDialogVisible = true;
      this.menuFormData.menu_id = row.menu_id
      this.menuFormData.menu_name = row.menu_name
      this.menuFormData.menu_amount = row.menu_amount
      this.menuFormData.menu_status = row.menu_status
      this.menuFormData.menu_type_id = row.menu_type_id
      this.menuFormData.restaurant_id = row.restaurant_id
    },
    handleSendData(menuForm) {
      this.$refs[menuForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createMenu({ postData: this.menuFormData }).then(response => {
                this.$refs[menuForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.menuDialogVisible = false
              });
            } else if (this.isEdit) {
              updateMenu(this.menuFormData.menu_id, { postData: this.menuFormData }).then(response => {
                this.$refs[menuForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.menuDialogVisible = false
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
    deleteMenu(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteMenu(id).then(response => {
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
