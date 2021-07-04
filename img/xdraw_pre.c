#include "xdraw.h"
int drawLine(const int x1, const int y1, const int x2, const int y2) {
	COORD coord = { 0 };
	double m = 0;
	int i, j, x, y = 0;
	if (x1 == x2) {
		y = xabsol(y1 - y2) + 1;
		coord.X = (short)x1;
		coord.Y = (y1 < y2) ? (short)y1 : (short)y2;
		for (i = 0; i < y; i++) {
			printPixel(coord, PIXEL);
			coord.Y++;
		}
		return 0;
	}
	m = (double)(y1 - y2) / (x1 - x2);
	i = x1 < x2 ? x1 : x2;
	x = x1 < x2 ? x2 : x1;
	for (; i < x; i++) {
		if (m == 0) y = 1;
		else if (m == (int)m) y = (int)xabsolf(m);
		else y = (int)xabsolf(m) + 1;
		coord.X = (short)i;
		for (j = 0; j < y; j++) {
			if (m >= 0) coord.Y = (short)(xroundf(m * (i - x1) + y1) + j);
			else coord.Y = (short)(xroundf(m * (i - x1) + y1) - j);
			printPixel(coord, PIXEL);
		}
	}
	return 0;
}
int fillTriangle(int x1, int y1, int x2, int y2, int x3, int y3) {
	int i, j, dy = 0;
	double m = 0;
	if ((x1 == x2 && y1 == y2) || (x1 == x3 && y1 == y3) || (x2 == x3 && y2 == y3))	return ERR_PARA;
	if (x2 < x1) { xswap(&x1, &x2, sizeof(int));xswap(&y1, &y2, sizeof(int)); }
	if (x3 < x1) { xswap(&x1, &x3, sizeof(int));xswap(&y1, &y3, sizeof(int)); }
	if (x3 < x2) { xswap(&x2, &x3, sizeof(int));xswap(&y2, &y3, sizeof(int)); }
	if (x2 == x3) {
		if (y3 < y2) xswap(&y3, &y2, sizeof(int));
		for (i = y2; i < (y3 + 1); i++) {
			drawLine(x1, y1, x2, i);
		}
	}
	else {
		m = (double)(y2 - y3) / (x2 - x3);
		if (m == 0) dy = 1;
		else if (m == (int)m) dy = (int)xabsolf(m);
		else dy = (int)xabsolf(m) + 1;
		printf("%f %d %d", m, (int)m, dy);
		for (i = x2; i <= x3; i++) {
			for (j = 0; j < dy; j++) {
				if (m >= 0)
					drawLine(x1, y1, i, xroundf(y2 + m * (i - x2)) + j);
				else
					drawLine(x1, y1, i, xroundf(y2 + m * (i - x2)) - j);
			}
		}
	}
	return 0;
}
int matrix_drawEdges(const MATRIX* const A, const size_t* const adj) {
	size_t i,j = 0;
	if (adj == NULL || A == NULL) return ERR_NUL;
	for (j = 0; j < A->cols; j++) {
		for (i = j; i < A->cols; i++) {
			if (adj[i + (j * A->cols)] == 1) {
				if (drawLine(xroundf(A->m[i]), xroundf(A->m[i + A->cols]), xroundf(A->m[j]), xroundf(A->m[j + A->cols]))) return ERR_FUNC;
			}
		}
	}
	return 0;
}