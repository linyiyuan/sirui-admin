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
          <el-form-item label="日期搜索：">
           <el-date-picker
              v-model="listQuery.add_date"
              align="right"
              type="date"
              placeholder="选择日期"
              :picker-options="pickerOptions">
            </el-date-picker>
          </el-form-item>
          <el-form-item label="IP搜索：">
            <el-input v-model="listQuery.ip" class="input-width" placeholder="请输入IP"></el-input>
          </el-form-item>
          <el-form-item label="状态搜索：">
            <el-select v-model="listQuery.status" placeholder="请选择状态">
              <el-option :value=1> 成功</el-option> 
              <el-option :value=0> 失败</el-option> 
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="danger" size="mini" @click="handleDelAgentRecord()">删除匹配信息
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="userTable"
                :data="list"
                style="width: 100%;"
                v-loading="listLoading" border>
        <el-table-column  label="匹配参数"  align="center">
          <template slot-scope="scope">{{scope.row.params}}</template>
        </el-table-column>
        <el-table-column  label="匹配结果" width="500" align="center">
          <template slot-scope="scope">{{scope.row.result}}</template>
        </el-table-column>
        <el-table-column  label="IP" width="150" align="center">
          <template slot-scope="scope">
           {{ scope.row.ip}}
          </template>
        </el-table-column>
        <el-table-column sortable label="状态" width="80" align="center">
          <template slot-scope="scope">{{scope.row.status | status}}</template>
        </el-table-column>
        <el-table-column  label="匹配时间" width="180" prop="add_time" align="center">
          <template slot-scope="scope">{{ scope.row.add_time | formatLoginTime}}</template>
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
  </div>
</template>
<script>
  import {agentMatchingRecord, delAgentMatchingRecord} from '@/api/passport/agentMatchingRecord'
  import {formatDate} from '@/utils/date';
  import store from '@/store'
  const defaultListQuery = {
    cur_page: 1,
    page_size: 15,
  };
  export default {
    name: "agentMatchingRecord",
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
        agentMatchingRecord(this.listQuery).then(response => {
          this.listLoading = false;
          this.list = response.data.list;
          this.total = response.data.total;
        });
      },
      handleDelAgentRecord() {
        this.$confirm('是否要进行该删除操作?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          delAgentMatchingRecord(this.listQuery).then(response=>{
            this.$message({
              message: '删除成功！',
              type: 'success',
              duration: 1000
            });
            this.getList();
          });
        })
      }
    }
  }
</script>
<style scoped>
  .input-width {
    width: 203px;
  }
</style>


