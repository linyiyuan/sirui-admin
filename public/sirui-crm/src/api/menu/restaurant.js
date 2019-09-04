import request from '@/utils/request'
export function restaurantList(params) {
  return request({
    url:'/menu/restaurant',
    method:'get',
    params:params
  })
}

export function createRestaurant(params) {
  return request({
    url:'/menu/restaurant',
    method:'post',
    data:params
  })
}

export function updateRestaurant(id, params) {
  return request({
    url:'/menu/restaurant' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteRestaurant(id) {
  return request({
    url:'/menu/restaurant' + '/' + id,
    method:'delete',
    data:id,
  })
}
