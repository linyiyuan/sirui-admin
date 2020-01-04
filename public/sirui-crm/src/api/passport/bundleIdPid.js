import request from '@/utils/request'
export function bundleIdPidList(params) {
  return request({
    url:'/passport/bundle_id_pid',
    method:'get',
    params:params
  })
}

export function createBundleIdPid(params) {
  return request({
    url:'/passport/bundle_id_pid',
    method:'post',
    data:params
  })
}

export function updateBundleIdPid(id, params) {
  return request({
    url:'/passport/bundle_id_pid' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteBundleIdPid(id) {
  return request({
    url:'/passport/bundle_id_pid' + '/' + id,
    method:'delete',
    data:id,
  })
}
