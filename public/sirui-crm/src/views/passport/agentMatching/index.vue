<template> 
  <div class="app-container">
    <el-card class="filter-container" shadow="never">
      <div>
        <i class="el-icon-search"></i>
        <span>筛选搜索</span>
        <el-button
          style="float:right"
          type="primary"
          @click="handleSearchList()"
          size="small">
          查询搜索
        </el-button>
        <el-button
          style="float:right;margin-right: 15px"
          @click="handleResetSearch()"
          size="small">
          重置
        </el-button>
      </div>
      <div style="margin-top: 15px">
        <el-form :inline="true" :model="listQuery" size="small">
          <el-form-item label="粘贴板信息搜索：">
            <el-input v-model="listQuery.affix" class="input-width" placeholder="请输入粘贴板信息"></el-input>
          </el-form-item>
          <el-form-item label="IP搜索：">
            <el-input v-model="listQuery.ip" class="input-width" placeholder="请输入IP"></el-input>
          </el-form-item>
          <el-form-item label="手机品牌搜索：">
            <el-input v-model="listQuery.phone" class="input-width" placeholder="请输入手机品牌"></el-input>
          </el-form-item>
          <el-form-item label="手机型号搜索：">
            <el-input v-model="listQuery.model" class="input-width" placeholder="请输入手机型号"></el-input>
          </el-form-item>
          <el-form-item label="OS搜索：">
            <el-select v-model="listQuery.os" placeholder="请选择OS">
              <el-option :value='AndroidOS'> AndroidOS</el-option> 
              <el-option :value='IOS'> IOS</el-option> 
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
    </el-card>
    <div class="table-container">
      <el-table ref="userTable"
                :data="list"
                style="width: 100%;"
                v-loading="listLoading" border>
        <el-table-column  label="粘贴板信息"  align="center">
          <template slot-scope="scope">{{scope.row.affix}}</template>
        </el-table-column>
        <el-table-column  label="IP" width="140" align="center">
          <template slot-scope="scope">{{scope.row.ip}}</template>
        </el-table-column>
        <el-table-column  label="手机品牌" width="150" align="center">
          <template slot-scope="scope">{{ scope.row.phone}}</template>
        </el-table-column>
        <el-table-column  label="手机型号" width="150" align="center">
          <template slot-scope="scope">{{ scope.row.model}}</template>
        </el-table-column>
        <el-table-column  label="手机品牌" width="150" align="center">
          <template slot-scope="scope">{{ scope.row.phone}}</template>
        </el-table-column>
        <el-table-column  label="设备系统" width="150" align="center">
          <template slot-scope="scope">{{ scope.row.os}}</template>
        </el-table-column>
        <el-table-column  label="系统版本" width="100" align="center">
          <template slot-scope="scope">{{ scope.row.version}}</template>
        </el-table-column>
        <el-table-column  label="设备型号" width="100" align="center">
          <template slot-scope="scope">{{ scope.row.sType}}</template>
        </el-table-column>
        <el-table-column  label="设备宽" width="90" align="center">
          <template slot-scope="scope">{{ scope.row.width}}</template>
        </el-table-column>
        <el-table-column  label="设备高" width="90" align="center">
          <template slot-scope="scope">{{ scope.row.height}}</template>
        </el-table-column>
        <el-table-column  label="平台ID" width="80" align="center">
          <template slot-scope="scope">{{ scope.row.pid}}</template>
        </el-table-column>
        <el-table-column  label="匹配时间" width="180" prop="add_time" align="center">
          <template slot-scope="scope">{{ scope.row.addTime | formatLoginTime}}</template>
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
        :page-sizes="[30,50,100]"
        :total="total">
      </el-pagination>
    </div>
  </div>
</template>
<script>
  import {agentMatching} from '@/api/passport/agentMatchingRecord'
  import {formatDate} from '@/utils/date';
  import store from '@/store'
  const defaultListQuery = {
    cur_page: 1,
    page_size: 30,
  };
  export default {
    name: "agentMatching",
    components:{},
    data() {
      return {
        listQuery: Object.assign({}, defaultListQuery),
        listLoading: true,
        list: null,
        total: null,
        multipleSelection: [],
        closeOrder:{
          dialogVisible:false,
          content:null,
          orderIds:[]
        },
        pickerOptions: {
          disabledDate(time) {
            return time.getTime() > Date.now();
          },
          shortcuts: [{
            text: '今天',
            onClick(picker) {
              picker.$emit('pick', new Date());
            }
          }, {
            text: '昨天',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', date);
            }
          }, {
            text: '一周前',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', date);
            }
          }]
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
      status(status) {
        if (status == 1) {
          return '成功';
        }else{
          return '失败';
        }
      }
    },
    methods: {
      handleViewResetPassword(data) {
          this.resetPasswordForm.uid = data.id
          this.resetPasswordDialogVisible = true;
      },
      handleResetSearch() {
        this.listQuery = Object.assign({}, defaultListQuery);
        this.getList();
      },
      handleSearchList() {
        this.listQuery.cur_page = 1;
        this.getList();
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
        agentMatching(this.listQuery).then(response => {
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


