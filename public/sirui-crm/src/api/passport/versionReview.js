import request from '@/utils/request'
export function versionReviewList(params) {
  return request({
    url:'/passport/version_review',
    method:'get',
    params:params
  })
}

export function createVersionReview(params) {
  return request({
    url:'/passport/version_review',
    method:'post',
    data:params
  })
}

export function updateVersionReview(id, params) {
  return request({
    url:'/passport/version_review' + '/' + id,
    method:'put',
    data:params
  })
}

export function changeVersionReview(id, params) {
  return request({
    url:'/passport/version_review' + '/' + id,
    method:'get',
    params:params
  })
}


export function deleteVersionReview(id) {
  return request({
    url:'/passport/version_review' + '/' + id,
    method:'delete',
    data:id,
  })
}
