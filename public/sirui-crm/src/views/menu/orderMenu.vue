<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>点餐操作</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="addMenuDialogVisible = false">点餐
      </el-button>
    </el-card>
    <div class="table-container">
      <el-calendar>
        <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
        <template slot="dateCell" slot-scope="{date, data}">
          <p :class="data.isSelected ? 'is-selected' : ''" v-if="data.day < nowDate" style="color: #c0c4cc">
            {{ data.day.split('-').slice(1).join('-') }} {{ data.day == nowDate ? '✔️' : ''}}
            <br><br>
            <del>{{ list[data.day]}}</del>
          </p>
          <p :class="data.isSelected ? 'is-selected' : ''" v-if="data.day >= nowDate" @click="addMenu(data,day)">
            {{ data.day.split('-').slice(1).join('-') }} {{ data.day == nowDate ? '✔️' : ''}}
            <br><br>
            {{ list[data.day]}}
          </p>
        </template>
      </el-calendar>
    </div>
    <!-- 重置密码 -->
    <el-dialog title="点餐平台" :visible.sync="addMenuDialogVisible" width="1400px" :close-on-click-modal="false">
      <el-form ref="addMenuForm" :model="addMenuForm" label-width="120px">
        <el-form-item label="餐厅选择">
          <el-select v-model="resetPasswordForm" filterable placeholder="请选择">
            <el-option v-for="item in restaurant" :key="item.value" :label="item.label" :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-card class="operate-container" shadow="never">
          <p>
            <i class="el-icon-tickets"></i>
            <span>菜谱分类</span>
            <p>
              <el-checkbox-group v-model="resetPasswordForm" lable="测试">
              <el-checkbox :label="col.value" :key="index" v-for="(col ,index) in menuList" >
                {{ col.label }}
              </el-checkbox>
            </el-checkbox-group>
            </p>
             <el-divider></el-divider>
          </p>
        </el-card>
        <el-card class="operate-container" shadow="never">
          <p>
            <i class="el-icon-tickets"></i>
            <span>订单信息</span>
            <el-form-item label="已选择的菜品：">
              <span style="font-size: 20px">五花肉</span> +
              <span style="font-size: 20px">五花肉</span> +
              <span style="font-size: 20px">五花肉</span>
            </el-form-item>
            <p style="float: right; margin-right: 120px">
              金额小计： <span style="color: red;font-size: 25px">￥100</span>
            </p>

          </p>
        </el-card>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="addMenuDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleResetPassword()">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { userList, deleteUser, resetPassword } from '@/api/user'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
};
const defaultResetPasswordForm = {
  uid: null,
  old_password: null,
  new_password: null,
  confirm_password: null,
}
export default {
  name: "userList",
  components: {},
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      date: new Date(),
      nowDate: null,
      list: [],
      total: null,
      operateType: null,
      addMenuDialogVisible: false,
      multipleSelection: [],
      closeOrder: {
        dialogVisible: false,
        content: null,
        orderIds: []
      },
      resetPasswordForm: Object.assign({}, defaultResetPasswordForm),
      restaurant: [{
        value: '选项1',
        label: '八少抄手'
      }, {
        value: '选项2',
        label: '超惠食'
      }, {
        value: '选项3',
        label: '超记'
      }, {
        value: '选项4',
        label: '一品鲜'
      }, {
        value: '选项5',
        label: '梅州腌面'
      }],
      menuList: [{
        value: '选项1',
        label: '八少抄手'
      }, {
        value: '选项2',
        label: '超惠食'
      }, {
        value: '选项3',
        label: '超记'
      }, {
        value: '选项4',
        label: '一品鲜'
      }, {
        value: '选项5',
        label: '梅州腌面'
      }],
    }
  },
  created() {
    let nowDate = new Date();
    this.nowDate = formatDate(nowDate, 'yyyy-MM-dd')

    this.list['2019-08-28'] = '青椒炒肉丝'
    this.list['2019-08-29'] = '酸辣土豆丝'
    this.list['2019-08-30'] = '辣子鸡饭'
    this.list['2019-08-31'] = '辣子鸡饭'
    this.list['2019-09-01'] = '水煮鱼饭'
    this.list['2019-09-02'] = '麻婆豆腐'
    this.list['2019-09-03'] = '麻婆豆腐'
    // this.getList();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
  },
  methods: {
    handleViewResetPassword(data) {
      this.resetPasswordForm.uid = data.id
      this.addMenuDialogVisible = true;
    },
    handleResetPassword() {
      resetPassword({ postData: this.resetPasswordForm }).then(response => {
        this.$message({
          message: '重置密码成功',
          type: 'success',
          duration: 1000
        });
        this.getList();
        this.resetPasswordForm = Object.assign({}, defaultResetPasswordForm)
        this.addMenuDialogVisible = false;
      });
    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleDeleteUser(index, row) {
      this.deleteUser(row.id);
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
      userList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    handleAddUser() {
      this.$router.push({ path: '/auth/user/create' });
    },
    handleEditUser(index, row) {
      this.$router.push({ path: '/auth/user/update', query: { id: row.id } });
    },
    deleteUser(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteUser(id).then(response => {
          this.$message({
            message: '删除成功！',
            type: 'success',
            duration: 1000
          });
          this.getList();
        });
      })
    },
    addMenu(date) {
      this.addMenuDialogVisible = true;
      console.log(date)
    }
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
