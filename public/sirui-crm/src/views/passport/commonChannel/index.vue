<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddCommonChannel">添加平台
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="commonChannelTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="平台ID" prop="platform_id" width="120" align="center">
          <template slot-scope="scope">{{scope.row.platform_id}}</template>
        </el-table-column>
        <el-table-column label="子包ID" width="220" align="center">
          <template slot-scope="scope">{{scope.row.resource_id}}</template>
        </el-table-column>
        <el-table-column label="渠道ID" width="220" align="center">
          <template slot-scope="scope">{{scope.row.channel_id}}</template>
        </el-table-column>
        <el-table-column label="渠道名" width="250" align="center">
          <template slot-scope="scope">{{scope.row.channel_name}}</template>
        </el-table-column>
        <el-table-column label="请求地址" width="300" align="center">
          <template slot-scope="scope">{{scope.row.api_url}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="200" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="200" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditCommonChannel(scope.$index, scope.row)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>

    <!-- 添加/修改渠道 -->
    <el-dialog title="添加/修改游戏" :visible.sync="commonChannelDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="commonChannelForm" :model="commonChannelFormData" label-width="120px">
        <el-form-item label="平台ID">
          <el-input type="text" v-model="commonChannelFormData.platform_id"  auto-complete="off" size="medium" :disabled='true' readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="子包ID" prop="resource_id">
          <el-input type="text" v-model="commonChannelFormData.resource_id" placeholder="请输入子包ID" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="渠道ID" prop="channel_id">
          <el-input type="text" v-model="commonChannelFormData.channel_id" placeholder="请输入渠道ID" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="渠道名" prop="channel_name">
          <el-input type="text" v-model="commonChannelFormData.channel_name" placeholder="请输入渠道名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="请求地址" prop="api_url">
          <el-input type="text" v-model="commonChannelFormData.api_url" placeholder="请输入请求地址" auto-complete="off" size="medium"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="commonChannelDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendData('commonChannelForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { commonChannelList, createCommonChannel, updateCommonChannel, deleteCommonChannel } from '@/api/passport/commonChannel'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
  platform_id: null,
};
const defaultCommonChannelFormData = {
  platform_id: null,
  resource_id: null,
  channel_id: null,
  channel_name: null,
  api_url: null,
}

export default {
  name: "commonChannelList",
  components: {},
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      commonChannelFormData: Object.assign({}, defaultCommonChannelFormData),
      listLoading: true,
      list: [],
      platformList: [],
      total: 0,
      commonChannelDialogVisible: false,
      isEdit: false,
    }
  },
  created() {
    this.listQuery.platform_id = this.$route.query.platform_id;
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
    handleDeleteCommonChannel(index, row) {
      this.deleteCommonChannel(row.id);
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
      commonChannelList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    handleAddCommonChannel() {
      this.isEdit = false;
      this.commonChannelDialogVisible = true;
      this.commonChannelFormData = Object.assign({}, defaultCommonChannelFormData);
      this.commonChannelFormData.platform_id = this.$route.query.platform_id;
    },
    handleEditCommonChannel(index, row) {
      this.isEdit = true;
      this.commonChannelDialogVisible = true;
      this.commonChannelFormData.platform_id = row.platform_id
      this.commonChannelFormData.resource_id = row.resource_id
      this.commonChannelFormData.channel_id = row.channel_id
      this.commonChannelFormData.channel_name = row.channel_name
      this.commonChannelFormData.api_url = row.api_url
    },
    handleSendData(commonChannelForm) {
      this.$refs[commonChannelForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createCommonChannel({ postData: this.commonChannelFormData }).then(response => {
                this.$refs[commonChannelForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonChannelDialogVisible = false
              });
            } else if (this.isEdit) {
              updateCommonChannel(this.commonChannelFormData.resource_id, { postData: this.commonChannelFormData }).then(response => {
                this.$refs[commonChannelForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonChannelDialogVisible = false
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
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
