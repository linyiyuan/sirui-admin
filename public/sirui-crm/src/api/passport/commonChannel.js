import request from '@/utils/request'
export function commonChannelList(params) {
  return request({
    url:'/passport/common_channel',
    method:'get',
    params:params
  })
}

export function createCommonChannel(params) {
  return request({
    url:'/passport/common_channel',
    method:'post',
    data:params
  })
}

export function updateCommonChannel(id, params) {
  return request({
    url:'/passport/common_channel' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteCommonChannel(id) {
  return request({
    url:'/passport/common_channel' + '/' + id,
    method:'delete',
    data:id,
  })
}
