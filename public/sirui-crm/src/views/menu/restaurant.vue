<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" 
                 icon="el-icon-plus"
                 type="primary"
                 size="mini"
                 @click="handleAddRestaurants"
                >添加餐馆
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="restaurantTable"
                :data="list"
                style="width: 100%;"
                v-loading="listLoading" border>
        <el-table-column sortable label="ID" prop="restaurantname" width="200" align="center">
          <template slot-scope="scope">{{scope.row.restaurant_id}}</template>
        </el-table-column>
        <el-table-column  label="餐馆名" width="500" align="center">
          <template slot-scope="scope">{{scope.row.restaurant_name}}</template>
        </el-table-column>
        <el-table-column sortable label="餐馆状态" prop="restaurant_status" width="250" align="center">
          <template slot-scope="scope">{{scope.row.restaurant_status | restaurant_status}}</template>
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
              @click="handleEditRestaurants(scope.$index, scope.row)"
            >编辑</el-button>
             <el-button
              icon="el-icon-delete"
              type="danger"
              size="mini"
              @click="handleDeleteRestaurant(scope.$index, scope.row)"
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
    <!-- 添加/修改菜品餐馆 -->
     <el-dialog title="添加/修改菜品餐馆" :visible.sync="restaurantDialogVisible"  width="700px" :close-on-click-modal="false">
        <el-form ref="restaurantForm" :model="restaurantFormData" label-width="120px" :rules="restaurantRules">
        <el-form-item label="餐馆名" prop="restaurant_name">
          <el-input type="text" v-model="restaurantFormData.restaurant_name" placeholder="请输入餐馆名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="restaurantFormData.restaurant_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button  size="small" @click="restaurantDialogVisible = false">取 消</el-button>
        <el-button  size="small" type="primary" @click="handleSendData('restaurantForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
  import {restaurantList, createRestaurant, updateRestaurant, deleteRestaurant} from '@/api/menu/restaurant'
  import {formatDate} from '@/utils/date';
  import store from '@/store'
  const defaultListQuery = {
    cur_page: 1,
    page_size: 15,
  };
  const defaultRestaurantFormData = {
    restaurant_id: null,
    restaurant_name: null,
    restaurant_status: 1,
  }
  export default {
    name: "restaurantList",
    components:{},
    data() {
      
      return {
        listQuery: Object.assign({}, defaultListQuery),
        listLoading: true,
        list: [],
        total: 0,
        restaurantDialogVisible:false,
        restaurantFormData:Object.assign({}, defaultRestaurantFormData),
        isEdit: false,
        restaurantRules: {
          restaurant_name: [
            { required: true, message: '请输入餐馆名', trigger: 'blur' }
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
      restaurant_status(restaurant_status) {
        if (restaurant_status == 1) {
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
      handleDeleteRestaurant(index, row){
        this.deleteRestaurant(row.restaurant_id);
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
        restaurantList(this.listQuery).then(response => {
          this.listLoading = false;
          this.list = response.data.list;
          this.total = response.data.total;
        });
      },
      handleAddRestaurants() {
        this.isEdit = false;
        this.restaurantDialogVisible = true;
        this.restaurantFormData = Object.assign({}, defaultRestaurantFormData);
      },
      handleEditRestaurants(index,row) {
        this.isEdit = true;
        this.restaurantDialogVisible = true;
        this.restaurantFormData.restaurant_id = row.restaurant_id
        this.restaurantFormData.restaurant_name = row.restaurant_name
        this.restaurantFormData.restaurant_status = row.restaurant_status
      },
      handleSendData(restaurantForm) {
      this.$refs[restaurantForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createRestaurant({ postData: this.restaurantFormData }).then(response => {
                this.$refs[restaurantForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.restaurantDialogVisible = false
              });
            } else if (this.isEdit) {
              updateRestaurant(this.restaurantFormData.id, { postData: this.restaurantFormData }).then(response => {
                this.$refs[restaurantForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.restaurantDialogVisible = false
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
      deleteRestaurant(id){
        this.$confirm('是否要进行该删除操作?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          deleteRestaurant(id).then(response=>{
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


