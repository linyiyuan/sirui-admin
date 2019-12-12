<template> 
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddVersionReview">添加审核
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="versionReviewTable" :data="list" style="width: 100%;" v-loading="listLoading" border>
        <el-table-column sortable label="包名" prop="versionReviewname" width="400" align="center">
          <template slot-scope="scope">{{scope.row.bundle_id}}</template>
        </el-table-column>
        <el-table-column label="包版本" width="200" align="center">
          <template slot-scope="scope">{{scope.row.bundle_version}}</template>
        </el-table-column>
        <el-table-column sortable label="审核状态" prop="version_review_status" width="250" align="center">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.version_review_status" :active-value=1 :inactive-value=0 active-color="#13ce66" inactive-color="#ff4949" active-text="允许热更" inactive-text="不允许热更" @change="changeVersionReviewStatus(scope.row)">
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column sortable label="默认指定渠道" prop="default_pid" width="250" align="center">
          <template slot-scope="scope">{{scope.row.default_pid}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="280" prop="created_at" align="center">
          <template slot-scope="scope">{{ scope.row.created_at}}</template>
        </el-table-column>
        <el-table-column sortable label="更新时间" width="280" prop="updated_at" align="center">
          <template slot-scope="scope">{{ scope.row.updated_at}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditVersionReview(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteVersionReview(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>
    <!-- 添加/修改审核 -->
    <el-dialog title="添加/修改审核" :visible.sync="versionReviewDialogVisible" width="700px" :close-on-click-modal="false">
      <el-form ref="versionReviewForm" :model="versionReviewFormData" label-width="120px" :rules="versionReviewRules">
        <el-form-item label="包名" prop="bundle_id">
          <el-input type="text" v-model="versionReviewFormData.bundle_id" placeholder="请输入包名" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="包版本" prop="bundle_version">
          <el-input type="text" v-model="versionReviewFormData.bundle_version" placeholder="请输入包版本" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="默认指定渠道" prop="default_pid">
          <el-input type="text" v-model="versionReviewFormData.default_pid" placeholder="请输入默认指定渠道" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="versionReviewFormData.version_review_status">
            <el-radio :label="1">启动</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="versionReviewDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleSendData('versionReviewForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { versionReviewList, createVersionReview, updateVersionReview, deleteVersionReview, changeVersionReview } from '@/api/passport/versionReview'
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
};
const defaultVersionReviewFormData = {
  id: null,
  bundle_id: null,
  bundle_version: null,
  default_pid: 0,
  version_review_status: 0,
}
export default {
  name: "versionReviewList",
  components: {},
  data() {

    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: [],
      total: 0,
      versionReviewDialogVisible: false,
      versionReviewFormData: Object.assign({}, defaultVersionReviewFormData),
      isEdit: false,
      versionReviewRules: {
        versionReview_name: [
          { required: true, message: '请输入审核名', trigger: 'blur' }
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
    versionReview_status(versionReview_status) {
      if (versionReview_status == 1) {
        return '开启'
      } else {
        return '关闭'
      }
    }
  },
  methods: {
    changeVersionReviewStatus(row) {
      changeVersionReview(row.id, {postData: row}).then(response => {
        if (response.errorCode == 200) {
           this.$message({
              message: '修改状态成功',
              type: 'success',
              duration: 1000
            });
         }       
      });

    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
      this.getList();
    },
    handleDeleteVersionReview(index, row) {
      this.deleteVersionReview(row.id);
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
      versionReviewList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    handleAddVersionReview() {
      this.isEdit = false;
      this.versionReviewDialogVisible = true;
      this.versionReviewFormData = Object.assign({}, defaultVersionReviewFormData);
    },
    handleEditVersionReview(index, row) {
      this.isEdit = true;
      this.versionReviewDialogVisible = true;
      this.versionReviewFormData.id = row.id
      this.versionReviewFormData.bundle_id = row.bundle_id
      this.versionReviewFormData.bundle_version = row.bundle_version
      this.versionReviewFormData.version_review_status = row.version_review_status
      this.versionReviewFormData.default_pid = row.default_pid
    },
    handleSendData(versionReviewForm) {
      this.$refs[versionReviewForm].validate((valid) => {
        if (valid) {
          this.$confirm('是否提交数据', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            if (!this.isEdit) {
              createVersionReview({ postData: this.versionReviewFormData }).then(response => {
                this.$refs[versionReviewForm].resetFields();
                this.$message({
                  message: '添加成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.versionReviewDialogVisible = false
              });
            } else if (this.isEdit) {
              updateVersionReview(this.versionReviewFormData.id, { postData: this.versionReviewFormData }).then(response => {
                this.$refs[versionReviewForm].resetFields();
                this.$message({
                  message: '修改成功',
                  type: 'success',
                  duration: 1000
                });
                this.getList();
                this.versionReviewDialogVisible = false
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
    deleteVersionReview(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteVersionReview(id).then(response => {
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
