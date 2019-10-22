import request from '@/utils/request'
export function gameUser(params) {
  return request({
    url:'/game/get_game_user_list',
    method:'get',
    params:params
  })
}

export function getGameUserRechargeInfo(params) {
  return request({
    url:'/game/get_order_info_by_account',
    method:'get',
    params:params
  })
}

export function getRechargeRank(params) {
  return request({
    url:'/game/get_recharge_rank',
    method:'get',
    params:params
  })
}

