import request from '@/utils/request'
export function commonGameList(params) {
  return request({
    url:'/passport/common_game',
    method:'get',
    params:params
  })
}

export function createCommonGame(params) {
  return request({
    url:'/passport/common_game',
    method:'post',
    data:params
  })
}

export function updateCommonGame(id, params) {
  return request({
    url:'/passport/common_game' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteCommonGame(id) {
  return request({
    url:'/passport/common_game' + '/' + id,
    method:'delete',
    data:id,
  })
}
