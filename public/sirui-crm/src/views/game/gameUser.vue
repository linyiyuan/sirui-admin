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
          <el-form-item label="玩家账号：">
            <el-input v-model="listQuery.account" class="input-width" placeholder="请输入玩家账号"></el-input>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
    </el-card>
    <div class="table-container">
      <el-table ref="menuTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="ID" prop="id" width="200" align="center">
          <template slot-scope="scope">{{scope.row.id}}</template>
        </el-table-column>
        <el-table-column label="账号名" align="center" width="160">
          <template slot-scope="scope">{{scope.row.account}}</template>
        </el-table-column>
        <el-table-column label="充值总额" align="center" width="140">
          <template slot-scope="scope">{{scope.row.amount}}</template>
        </el-table-column>
        <el-table-column sortable label="手机号码" width="120" prop="phone_num" align="center">
          <template slot-scope="scope">{{scope.row.phone_num}}</template>
        </el-table-column>
        <el-table-column sortable label="渠道" width="180" prop="platform_info" align="center">
          <template slot-scope="scope">{{scope.row.platform_info}}</template>
        </el-table-column>
        <el-table-column label="游戏类型" width="180" align="center">
          <template slot-scope="scope">{{scope.row.game_type}}</template>
        </el-table-column>
        <el-table-column sortable label="注册IP" prop="reg_ip" width="150" align="center">
          <template slot-scope="scope">{{scope.row.reg_ip }}</template>
        </el-table-column>
        <el-table-column sortable label="注册时间" width="220" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.reg_date}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-delete" type="warning" size="mini" @click="handleGetGameUserRechargeInfo(scope.$index, scope.row)">查看该用户充值信息</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteGameUser(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>
    <!-- 充值信息 -->
    <el-dialog title="充值信息" :visible.sync="orderInfoDialogVisible" width="700px" :close-on-click-modal="false">
      <el-table  :data="gameUserRechargeInfo.order_info_by_date" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="日期" prop="date" width="200" align="center">
          <template slot-scope="scope">{{scope.row.date}}</template>
        </el-table-column>
        <el-table-column label="充值总额" align="center">
          <template slot-scope="scope">{{scope.row.amount}}</template>
        </el-table-column>
      </el-table>
      <div slot="footer">
        <el-button size="small" @click="orderInfoDialogVisible = false">关 闭</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { gameUser, getGameUserRechargeInfo } from '@/api/game/gameUser'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
  account: null
};
export default {
  name: "gameUser",
  components: {},
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: [],
      gameUserRechargeInfo: [],
      orderInfoDialogVisible: false,
      total: 0,
    }
  },
  created() {
    this.listQuery.account = this.$route.query.account
    this.getList();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
  },
  methods: {
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleDeleteGameUser(index, row) {
      this.deleteGameUser(row.id);
    },
    handleGetGameUserRechargeInfo(index, row) {
      this.orderInfoDialogVisible = true;
      getGameUserRechargeInfo({ 'account': row.account }).then(response => {
        this.listLoading = false;
        this.gameUserRechargeInfo = response.data.list;
      });
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
      gameUser(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    deleteGameUser(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteGameUser(id).then(response => {
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
