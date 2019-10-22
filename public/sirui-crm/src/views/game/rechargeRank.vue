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
        <el-table-column label="账号名" align="center">
          <template slot-scope="scope">{{scope.row.account}}</template>
        </el-table-column>
        <el-table-column sortable label="手机号码" width="120" prop="phone_num" align="center">
          <template slot-scope="scope">{{scope.row.phone_num}}</template>
        </el-table-column>
        <el-table-column sortable label="充值总额" width="120" prop="phone_num" align="center">
          <template slot-scope="scope">{{scope.row.amount}}</template>
        </el-table-column>  
         <el-table-column sortable label="游戏类型" width="120" prop="phone_num" align="center">
          <template slot-scope="scope">{{scope.row.game_type}}</template>
        </el-table-column>   
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-delete" type="warning" size="mini" @click="handleGetGameUser(scope.$index, scope.row)">查看该用户详细信息</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15,50,100]" :total="total">
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
import { gameUser, getRechargeRank } from '@/api/game/gameUser'
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
    handleGetGameUser(index, row) {
      this.$router.push({path:'/game/gameUser', query: {account: row.account}});
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
      getRechargeRank(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
