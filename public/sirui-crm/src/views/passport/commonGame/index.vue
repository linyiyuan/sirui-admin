<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddCommonGame">添加游戏
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="commonGameTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="游戏ID" prop="game_id" width="100" align="center">
          <template slot-scope="scope">{{scope.row.game_id}}</template>
        </el-table-column>
        <el-table-column label="游戏图标" width="200" align="center">
          <template slot-scope="scope">{{scope.row.game_icon}}</template>
        </el-table-column>
        <el-table-column label="游戏名" width="200" align="center">
          <template slot-scope="scope">{{scope.row.game_name}}</template>
        </el-table-column>
        <el-table-column label="游戏秘钥" width="400" align="center">
          <template slot-scope="scope">{{scope.row.game_secret}}</template>
        </el-table-column>
        <el-table-column sortable label="状态" prop="game_status" width="100" align="center">
          <template slot-scope="scope">{{ scope.row.game_status | game_status}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="200" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="200" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditCommonGame(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-search" type="warning" size="mini" @click="handleViewPlatform(scope.row)">查看平台</el-button>
            <el-button icon="el-icon-search" type="success" size="mini" @click="handleAddCommonPlatform(scope.row)">添加平台</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>

    <!-- 添加/修改审核 -->
    <el-dialog title="添加/修改游戏" :visible.sync="commonGameDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="commonGameForm" :model="commonGameFormData" label-width="120px">
        <el-form-item label="游戏ID" prop="game_id">
          <el-input type="text" v-model="commonGameFormData.game_id" placeholder="请输入游戏ID" auto-complete="off" size="medium" :disabled='true' readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="游戏名" prop="game_name">
          <el-input type="text" v-model="commonGameFormData.game_name" placeholder="请输入游戏名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="游戏秘钥" prop="game_secret">
          <el-input type="text" v-model="commonGameFormData.game_secret" placeholder="请输入游戏秘钥" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="commonGameFormData.game_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="commonGameDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendData('commonGameForm')">确 定</el-button>
      </div>
    </el-dialog>


     <!-- 平台显示 -->
    <el-dialog title="平台查看" :visible.sync="commonPlatformDialogVisible" width="900px" :close-on-click-modal="false">
      <el-card class="operate-container" shadow="never">
        <i class="el-icon-tickets"></i>
        <span>数据列表</span>
      </el-card>
      <el-table ref="commonPlatformTable" :data="platformList" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="游戏ID" prop="game_id" width="90" align="center">
          <template slot-scope="scope">{{scope.row.game_id}}</template>
        </el-table-column>
        <el-table-column label="平台ID" width="100" align="center">
          <template slot-scope="scope">{{scope.row.platform_id}}</template>
        </el-table-column>
        <el-table-column label="平台名" width="150" align="center">
          <template slot-scope="scope">{{scope.row.platform_name}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="180" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditCommonPlatform(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-search" type="warning" size="mini" @click="handleViewPlatChannel(scope.row)">查看渠道</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div slot="footer">
        <el-button size="small" @click="commonPlatformDialogVisible = false">关 闭</el-button>
      </div>
    </el-dialog>

     <!-- 添加/修改 平台 -->
     <el-dialog title="添加/修改平台" :visible.sync="commonPlatformEditDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="commonPlatformForm" :model="commonPlatformFormData" label-width="120px">
        <el-form-item label="游戏ID" prop="game_id">
          <el-input type="text" v-model="commonPlatformFormData.game_id" auto-complete="off" size="medium" :disabled='true' readonly="true"></el-input>
        </el-form-item>
        <el-form-item label="平台ID" prop="game_name">
          <el-input type="text" v-model="commonPlatformFormData.platform_id" placeholder="请输入平台ID" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="平台名称" prop="game_secret">
          <el-input type="text" v-model="commonPlatformFormData.platform_name" placeholder="请输入平台名称" auto-complete="off" size="medium"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="commonPlatformEditDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendDataByPlatform('commonPlatformForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { commonGameList, createCommonGame, updateCommonGame, deleteCommonGame } from '@/api/passport/commonGame'
import { commonPlatformList, createCommonPlatform, updateCommonPlatform, deleteCommonPlatform } from '@/api/passport/commonPlatform'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
};
const defaultCommonGameFormData = {
  id: null,
  game_id: null,
  game_name: null,
  game_secret: 0,
  game_status: 0,
}
const defaultCommonPlatformFormData = {
  platform_id: null,
  platform_name: null,
  game_id: null,
}
export default {
  name: "commonGameList",
  components: {},
  data() {

    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: [],
      platformList: [],
      total: 0,
      commonGameDialogVisible: false,
      commonPlatformDialogVisible: false,
      commonPlatformEditDialogVisible: false,
      commonGameFormData: Object.assign({}, defaultCommonGameFormData),
      commonPlatformFormData: Object.assign({}, defaultCommonPlatformFormData),
      isEdit: false,
      isEditByPlatform: false,
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
    game_status(game_status) {
      if (game_status == 1) {
        return '开启'
      } else {
        return '关闭'
      }
    }
  },
  methods: {
    changeCommonGameStatus(row) {
      changeCommonGame(row.id, {postData: row}).then(response => {
        if (response.errorCode == 200) {
           this.$message({
              message: '修改状态成功',
              type: 'success',
              duration: 1000
            });
         }       
      });

    },
    handleViewPlatChannel(row) {
        this.$router.push({ path: '/passport/common_channel', query: {platform_id: row.platform_id} });
    },
    handleAddCommonPlatform(row) {
        this.isEditByPlatform = false;
        this.commonPlatformEditDialogVisible = true;
        this.commonPlatformFormData = Object.assign({}, defaultCommonPlatformFormData);
        this.commonPlatformFormData.game_id = row.game_id
    },
    handleEditCommonPlatform(index,row) {
      this.isEditByPlatform = true;
      this.commonPlatformEditDialogVisible = true;
      this.commonPlatformFormData.game_id = row.game_id
      this.commonPlatformFormData.platform_id = row.platform_id
      this.commonPlatformFormData.platform_name = row.platform_name
    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleViewPlatform(row) {
      this.commonPlatformDialogVisible = true
      this.getPlatformList(row.game_id)
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleDeleteCommonGame(index, row) {
      this.deleteCommonGame(row.id);
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
      commonGameList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    getPlatformList(game_id) {
      commonPlatformList({game_id: game_id}).then(response => {
        this.platformList = response.data.list;
      });
    },
    handleAddCommonGame() {
      this.isEdit = false;
      this.commonGameDialogVisible = true;
      this.commonGameFormData = Object.assign({}, defaultCommonGameFormData);
    },
    handleEditCommonGame(index, row) {
      this.isEdit = true;
      this.commonGameDialogVisible = true;
      this.commonGameFormData.id = row.id
      this.commonGameFormData.game_id = row.game_id
      this.commonGameFormData.game_name = row.game_name
      this.commonGameFormData.game_status = row.game_status
      this.commonGameFormData.game_secret = row.game_secret
    },
    handleSendDataByPlatform(commonPlatformForm) {
      this.$refs[commonPlatformForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEditByPlatform) {
              createCommonPlatform({ postData: this.commonPlatformFormData }).then(response => {
                this.$refs[commonPlatformForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonPlatformDialogVisible = false
              });
            } else if (this.isEditByPlatform) {
              updateCommonPlatform(this.commonPlatformFormData.platform_id, { postData: this.commonPlatformFormData }).then(response => {
                this.$refs[commonPlatformForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonPlatformDialogVisible = false
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
    handleSendData(commonGameForm) {
      this.$refs[commonGameForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createCommonGame({ postData: this.commonGameFormData }).then(response => {
                this.$refs[commonGameForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonGameDialogVisible = false
              });
            } else if (this.isEdit) {
              updateCommonGame(this.commonGameFormData.id, { postData: this.commonGameFormData }).then(response => {
                this.$refs[commonGameForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.commonGameDialogVisible = false
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
