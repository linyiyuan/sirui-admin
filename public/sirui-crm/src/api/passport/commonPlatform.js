import request from '@/utils/request'
export function commonPlatformList(params) {
  return request({
    url:'/passport/common_platform',
    method:'get',
    params:params
  })
}

export function createCommonPlatform(params) {
  return request({
    url:'/passport/common_platform',
    method:'post',
    data:params
  })
}

export function updateCommonPlatform(id, params) {
  return request({
    url:'/passport/common_platform' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteCommonPlatform(id) {
  return request({
    url:'/passport/common_platform' + '/' + id,
    method:'delete',
    data:id,
  })
}
